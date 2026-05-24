<?php

namespace App\Services;

use App\Models\Locale;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class LocaleService
{
    public function default(): string
    {
        return config('locales.default', 'ar');
    }

    public function fallback(): string
    {
        return config('locales.fallback', 'en');
    }

    /** @return Collection<int, Locale> */
    public function active(): Collection
    {
        return Cache::remember('locales.active', 3600, function () {
            return Locale::query()
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->get();
        });
    }

    public function isSupported(string $locale): bool
    {
        return in_array($locale, config('locales.supported', []), true);
    }

    public function direction(string $locale): string
    {
        $record = $this->active()->firstWhere('code', $locale);

        return $record?->direction ?? ($locale === 'ar' ? 'rtl' : 'ltr');
    }

    public function clearCache(): void
    {
        Cache::forget('locales.active');
    }
}
