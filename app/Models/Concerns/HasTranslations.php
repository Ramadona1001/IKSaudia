<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasTranslations
{
    public function translate(?string $locale = null): ?Model
    {
        $locale ??= app()->getLocale();

        $translations = $this->relationLoaded('translations')
            ? $this->translations
            : $this->translations()->get();

        return $translations->firstWhere('locale', $locale)
            ?? $translations->firstWhere('locale', config('locales.fallback'));
    }

    public function translationFor(string $locale): ?Model
    {
        return $this->translations->firstWhere('locale', $locale);
    }

    abstract public function translations(): HasMany;
}
