<?php

namespace App\Services;

use App\Models\SiteSetting;
use App\Models\SiteSettingTranslation;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SettingsService
{
  protected ?array $memory = null;

  public function get(string $key, mixed $default = null, ?string $locale = null): mixed
  {
    $locale ??= app()->getLocale();
    $bag = $this->all($locale);

    return data_get($bag, $key, $default);
  }

  public function group(string $group, ?string $locale = null): array
  {
    $locale ??= app()->getLocale();

    return $this->all($locale)[$group] ?? [];
  }

  /**
   * Nested settings bag: [group => [key => value]].
   *
   * @return array<string, array<string, mixed>>
   */
  public function all(?string $locale = null): array
  {
    $locale ??= app()->getLocale();

    if ($this->memory !== null) {
      return $this->memory;
    }

    if (! $this->cacheEnabled()) {
      return $this->loadFromDatabase($locale);
    }

    $cacheKey = $this->cacheKey($locale);

    return Cache::remember($cacheKey, $this->cacheTtl(), fn () => $this->loadFromDatabase($locale));
  }

  public function url(string $key, ?string $locale = null): ?string
  {
    $path = $this->get($key, null, $locale);

    if (! is_string($path) || $path === '') {
      return null;
    }

    if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
      return $path;
    }

    return Storage::disk('public')->url($path);
  }

  /**
   * @param  array<string, mixed>  $formData  Nested form state from Filament
   */
  public function syncFromForm(array $formData): void
  {
    DB::transaction(function () use ($formData): void {
      foreach ($this->definitions() as $dotKey => $definition) {
        $value = data_get($formData, $dotKey);

        if ($value === null && ! Arr::get($definition, 'translatable')) {
          $this->persistScalar($dotKey, $definition, null);

          continue;
        }

        if (Arr::get($definition, 'translatable')) {
          $this->persistTranslatable($dotKey, $definition, is_array($value) ? $value : []);
        } else {
          $this->persistScalar($dotKey, $definition, $value);
        }
      }
    });

    $this->clearCache();
  }

  /**
   * Persist only settings present in $formData (does not wipe omitted keys).
   *
   * @param  array<string, mixed>  $formData
   */
  public function syncPartialFromForm(array $formData): void
  {
    DB::transaction(function () use ($formData): void {
      foreach ($this->definitions() as $dotKey => $definition) {
        if (! $this->formDataHas($formData, $dotKey)) {
          continue;
        }

        $value = data_get($formData, $dotKey);

        if (Arr::get($definition, 'translatable')) {
          $this->persistTranslatable($dotKey, $definition, is_array($value) ? $value : []);
        } else {
          $this->persistScalar($dotKey, $definition, $value);
        }
      }
    });

    $this->clearCache();
  }

  /**
   * @param  array<string, mixed>  $formData
   */
  protected function formDataHas(array $formData, string $dotKey): bool
  {
    $segments = explode('.', $dotKey);
    $current = $formData;

    foreach ($segments as $segment) {
      if (! is_array($current) || ! array_key_exists($segment, $current)) {
        return false;
      }

      $current = $current[$segment];
    }

    return true;
  }

  /**
   * Build nested form state for Filament.
   *
   * @return array<string, mixed>
   */
  public function toFormState(?string $locale = null): array
  {
    $locale ??= app()->getLocale();
    $state = [];

    foreach ($this->definitions() as $dotKey => $definition) {
      if (Arr::get($definition, 'translatable')) {
        $localized = [];
        foreach ($this->supportedLocales() as $code) {
          $localized[$code] = $this->get($dotKey, null, $code);
        }
        data_set($state, $dotKey, $localized);
      } else {
        data_set($state, $dotKey, $this->get($dotKey, null, $locale));
      }
    }

    return $state;
  }

  public function clearCache(): void
  {
    $this->memory = null;

    foreach ($this->supportedLocales() as $locale) {
      Cache::forget($this->cacheKey($locale));
    }

    Cache::forget($this->cacheKey('_all'));
  }

  public function definitions(): array
  {
    return config('website-settings.definitions', []);
  }

  /** @return list<string> */
  public function supportedLocales(): array
  {
    return config('website-settings.locales', ['ar', 'en']);
  }

  protected function loadFromDatabase(string $locale): array
  {
    $settings = SiteSetting::query()
      ->with('translations')
      ->orderBy('group')
      ->orderBy('sort_order')
      ->get();

    $bag = [];

    foreach ($settings as $setting) {
      $shortKey = $this->shortKey($setting->key);
      $group = $setting->group;

      if ($setting->is_translatable) {
        $translation = $setting->translationFor($locale)
          ?? $setting->translationFor(config('locales.fallback', 'en'));
        $value = $this->castOut($setting->type, $translation?->value);
      } else {
        $value = $this->castOut($setting->type, $setting->value);
      }

      if (! isset($bag[$group])) {
        $bag[$group] = [];
      }

      $bag[$group][$shortKey] = $value;
    }

    return $bag;
  }

  protected function persistScalar(string $dotKey, array $definition, mixed $value): void
  {
    $setting = $this->findOrCreateSetting($dotKey, $definition);
    $setting->update([
      'value' => $this->castIn($definition['type'], $value),
      'is_translatable' => false,
    ]);
  }

  /**
   * @param  array<string, mixed>  $values
   */
  protected function persistTranslatable(string $dotKey, array $definition, array $values): void
  {
    $setting = $this->findOrCreateSetting($dotKey, $definition);
    $setting->update(['is_translatable' => true, 'value' => null]);

    foreach ($this->supportedLocales() as $locale) {
      $raw = $values[$locale] ?? null;
      SiteSettingTranslation::query()->updateOrCreate(
        [
          'site_setting_id' => $setting->id,
          'locale' => $locale,
        ],
        [
          'value' => $this->castIn($definition['type'], $raw),
        ],
      );
    }
  }

  protected function findOrCreateSetting(string $dotKey, array $definition): SiteSetting
  {
    [$group] = explode('.', $dotKey, 2);

    return SiteSetting::query()->firstOrCreate(
      ['key' => $dotKey],
      [
        'group' => $group,
        'type' => $definition['type'],
        'label' => $definition['label'] ?? $dotKey,
        'is_translatable' => (bool) ($definition['translatable'] ?? false),
        'sort_order' => 0,
      ],
    );
  }

  protected function shortKey(string $dotKey): string
  {
    $parts = explode('.', $dotKey, 2);

    return $parts[1] ?? $dotKey;
  }

  protected function castIn(string $type, mixed $value): ?string
  {
    if ($value === null) {
      return null;
    }

    return match ($type) {
      'json', 'boolean' => json_encode($value, JSON_UNESCAPED_UNICODE),
      default => is_string($value) ? $value : (string) $value,
    };
  }

  protected function castOut(string $type, ?string $value): mixed
  {
    if ($value === null || $value === '') {
      return match ($type) {
        'json' => [],
        'boolean' => false,
        default => null,
      };
    }

    return match ($type) {
      'json' => json_decode($value, true) ?? [],
      'boolean' => filter_var($value, FILTER_VALIDATE_BOOLEAN),
      default => $value,
    };
  }

  protected function cacheEnabled(): bool
  {
    return (bool) config('website-settings.cache.enabled', true);
  }

  protected function cacheKey(string $locale): string
  {
    $version = config('website-settings.cache.version', 1);
    $prefix = config('website-settings.cache.prefix', 'site_settings');

    return "{$prefix}.v{$version}.{$locale}";
  }

  protected function cacheTtl(): int
  {
    return (int) config('website-settings.cache.ttl', 3600);
  }
}
