<?php

namespace App\Support;

use Illuminate\Support\Facades\Lang;

final class LocaleHelper
{
    /**
     * Ordered process step keys for the homepage timeline.
     *
     * @return list<string>
     */
    public static function processStepKeys(): array
    {
        return [
            'consult_assessment',
            'engineering_design',
            'manufacturing_supply',
            'field_execution',
            'support_maintenance',
        ];
    }

    /**
     * Hero highlight point keys.
     *
     * @return list<string>
     */
    public static function heroPointKeys(): array
    {
        return ['facility', 'expertise', 'standards', 'partners'];
    }

    /**
     * Homepage stat counters: count, suffix, translation key under home.stats.
     *
     * @return list<array{count: int, suffix: string, label: string}>
     */
    public static function homeStats(): array
    {
        return [
            ['count' => 25, 'suffix' => '+', 'label' => 'years_experience'],
            ['count' => 150, 'suffix' => '+', 'label' => 'projects_delivered'],
            ['count' => 50, 'suffix' => '+', 'label' => 'energy_clients'],
            ['count' => 100, 'suffix' => '%', 'label' => 'safety_commitment'],
        ];
    }

    /**
     * Fallback industry keys when CMS data is empty.
     *
     * @return list<string>
     */
    public static function industryFallbackKeys(): array
    {
        return ['oil_gas', 'mining', 'subsea', 'petrochemicals'];
    }

    /**
     * Resolve a translation with fallback to the application fallback locale.
     */
    public static function trans(string $key, array $replace = [], ?string $locale = null): string
    {
        $locale ??= app()->getLocale();
        $line = Lang::get($key, $replace, $locale);

        if ($line !== $key) {
            return $line;
        }

        $fallback = config('app.fallback_locale', 'en');

        if ($locale !== $fallback) {
            $line = Lang::get($key, $replace, $fallback);

            if ($line !== $key) {
                return $line;
            }
        }

        return $key;
    }
}
