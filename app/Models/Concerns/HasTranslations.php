<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasTranslations
{
    public function translate(?string $locale = null): ?Model
    {
        $locale ??= app()->getLocale();
        $fallback = (string) config('locales.fallback', 'en');

        $translations = $this->relationLoaded('translations')
            ? $this->translations
            : $this->translations()->get();

        $match = $translations->firstWhere('locale', $locale)
            ?? ($fallback !== $locale ? $translations->firstWhere('locale', $fallback) : null)
            ?? $translations->first(fn (Model $translation): bool => filled($translation->title ?? null));

        return $match instanceof Model ? $match : null;
    }

    public function translationFor(string $locale): ?Model
    {
        return $this->translations->firstWhere('locale', $locale);
    }

    abstract public function translations(): HasMany;
}
