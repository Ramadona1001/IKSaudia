<?php

use App\Services\SettingsService;

if (! function_exists('setting')) {
  /**
   * Get a website setting by dot path (e.g. contact.phones, general.site_name).
   */
  function setting(string $key, mixed $default = null, ?string $locale = null): mixed
  {
    return app(SettingsService::class)->get($key, $default, $locale);
  }
}

if (! function_exists('settings')) {
  /**
   * Get all settings or a single group (general, footer, contact, …).
   */
  function settings(?string $group = null, ?string $locale = null): array
  {
    $service = app(SettingsService::class);

    if ($group === null) {
      return $service->all($locale);
    }

    return $service->group($group, $locale);
  }
}

if (! function_exists('setting_url')) {
  /**
   * Resolve a public disk URL for an image setting path.
   */
  function setting_url(string $key, ?string $locale = null): ?string
  {
    return app(SettingsService::class)->url($key, $locale);
  }
}
