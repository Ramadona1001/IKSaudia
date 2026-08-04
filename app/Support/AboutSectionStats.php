<?php

namespace App\Support;

final class AboutSectionStats
{
    private const STAT_FALLBACK_KEYS = ['projects', 'clients', 'countries', 'satisfaction'];

    /**
     * @return list<array{count: int, suffix: string, variant: string, label: string}>
     */
    public static function defaultStatsForLocale(string $locale): array
    {
        $variants = ['gold', 'blue', 'gold', 'blue'];
        $counts = [500, 150, 12, 98];
        $suffixes = ['+', '+', '', '%'];
        $resolved = [];

        foreach (self::STAT_FALLBACK_KEYS as $i => $key) {
            $resolved[] = [
                'count' => $counts[$i],
                'suffix' => $suffixes[$i],
                'variant' => $variants[$i],
                'label' => __('front.home.about.stats.'.$key, [], $locale),
            ];
        }

        return $resolved;
    }

    /** @return array{count: int, suffix: string, label: string} */
    public static function defaultYearsBadgeForLocale(string $locale): array
    {
        return [
            'count' => 25,
            'suffix' => '+',
            'label' => __('front.home.about.years_excellence', [], $locale),
        ];
    }

    /**
     * @return list<array{count: int, suffix: string, label: string, variant: string, delay: int}>
     */
    public static function forLocale(?array $settings, string $locale): array
    {
        $settings = self::normalizeSettings($settings);
        $configured = $settings['stats'][$locale] ?? null;
        $defaults = self::defaultStatsForLocale($locale);
        $delays = [0, 100, 200, 300];
        $resolved = [];

        for ($i = 0; $i < 4; $i++) {
            $row = is_array($configured[$i] ?? null) ? $configured[$i] : [];
            $fallback = $defaults[$i];

            $resolved[] = [
                'count' => (int) ($row['count'] ?? $fallback['count']),
                'suffix' => (string) ($row['suffix'] ?? $fallback['suffix']),
                'label' => self::nonEmptyString($row['label'] ?? null, $fallback['label']),
                'variant' => in_array($row['variant'] ?? '', ['gold', 'blue'], true)
                    ? $row['variant']
                    : $fallback['variant'],
                'delay' => $delays[$i],
            ];
        }

        return $resolved;
    }

    /** @return array{count: int, suffix: string, label: string} */
    public static function yearsBadgeForLocale(?array $settings, string $locale): array
    {
        $settings = self::normalizeSettings($settings);
        $configured = is_array($settings['years_badge'][$locale] ?? null)
            ? $settings['years_badge'][$locale]
            : [];
        $fallback = self::defaultYearsBadgeForLocale($locale);

        return [
            'count' => (int) ($configured['count'] ?? $fallback['count']),
            'suffix' => (string) ($configured['suffix'] ?? $fallback['suffix']),
            'label' => self::nonEmptyString($configured['label'] ?? null, $fallback['label']),
        ];
    }

    /**
     * Normalize, fill missing rows, and coerce stat values for storage.
     *
     * @param  array<string, mixed>  $settings
     * @return array{stats: array<string, list<array<string, mixed>>>, years_badge: array<string, array<string, mixed>>}
     */
    public static function sanitizeSettings(array $settings): array
    {
        $settings = self::normalizeSettings($settings);
        $sanitizedStats = [];
        $sanitizedBadges = [];

        foreach (['ar', 'en'] as $locale) {
            $configured = is_array($settings['stats'][$locale] ?? null)
                ? $settings['stats'][$locale]
                : [];
            $defaults = self::defaultStatsForLocale($locale);
            $stats = [];

            for ($i = 0; $i < 4; $i++) {
                $row = is_array($configured[$i] ?? null) ? $configured[$i] : [];
                $fallback = $defaults[$i];

                $stats[] = [
                    'count' => max(0, (int) ($row['count'] ?? $fallback['count'])),
                    'suffix' => (string) ($row['suffix'] ?? $fallback['suffix'] ?? ''),
                    'variant' => in_array($row['variant'] ?? '', ['gold', 'blue'], true)
                        ? $row['variant']
                        : $fallback['variant'],
                    'label' => self::nonEmptyString($row['label'] ?? null, $fallback['label']),
                ];
            }

            $sanitizedStats[$locale] = $stats;

            $badge = is_array($settings['years_badge'][$locale] ?? null)
                ? $settings['years_badge'][$locale]
                : [];
            $badgeDefault = self::defaultYearsBadgeForLocale($locale);

            $sanitizedBadges[$locale] = [
                'count' => max(0, (int) ($badge['count'] ?? $badgeDefault['count'])),
                'suffix' => (string) ($badge['suffix'] ?? $badgeDefault['suffix']),
                'label' => self::nonEmptyString($badge['label'] ?? null, $badgeDefault['label']),
            ];
        }

        return [
            'stats' => $sanitizedStats,
            'years_badge' => $sanitizedBadges,
        ];
    }

    /**
     * Convert legacy single-locale / merged label shapes to ar + en buckets.
     *
     * @return array<string, mixed>
     */
    public static function normalizeSettings(?array $settings): array
    {
        $settings ??= [];

        if (isset($settings['stats']['ar']) || isset($settings['stats']['en'])) {
            return $settings;
        }

        if (isset($settings['stats'][0]) && is_array($settings['stats'][0])) {
            $settings['stats'] = self::migrateLegacyStatsList($settings['stats']);
        }

        if (isset($settings['years_badge']['count']) || isset($settings['years_badge']['labels'])) {
            $settings['years_badge'] = self::migrateLegacyYearsBadge($settings['years_badge']);
        }

        return $settings;
    }

    /**
     * @param  list<array<string, mixed>>  $legacy
     * @return array{ar: list<array<string, mixed>>, en: list<array<string, mixed>>}
     */
    private static function migrateLegacyStatsList(array $legacy): array
    {
        $ar = [];
        $en = [];

        foreach ($legacy as $i => $row) {
            if (! is_array($row)) {
                continue;
            }

            $labels = is_array($row['labels'] ?? null) ? $row['labels'] : [];
            $arDefault = self::defaultStatsForLocale('ar')[$i] ?? [];
            $enDefault = self::defaultStatsForLocale('en')[$i] ?? [];

            $ar[] = [
                'count' => (int) ($row['count'] ?? $arDefault['count'] ?? 0),
                'suffix' => (string) ($row['suffix'] ?? $arDefault['suffix'] ?? ''),
                'variant' => $row['variant'] ?? $arDefault['variant'] ?? 'gold',
                'label' => self::nonEmptyString($labels['ar'] ?? $row['label'] ?? null, $arDefault['label'] ?? ''),
            ];

            $en[] = [
                'count' => (int) ($row['count'] ?? $enDefault['count'] ?? 0),
                'suffix' => (string) ($row['suffix'] ?? $enDefault['suffix'] ?? ''),
                'variant' => $row['variant'] ?? $enDefault['variant'] ?? 'gold',
                'label' => self::nonEmptyString($labels['en'] ?? $row['label'] ?? null, $enDefault['label'] ?? ''),
            ];
        }

        return ['ar' => $ar, 'en' => $en];
    }

    /**
     * @param  array<string, mixed>  $legacy
     * @return array{ar: array<string, mixed>, en: array<string, mixed>}
     */
    private static function migrateLegacyYearsBadge(array $legacy): array
    {
        $labels = is_array($legacy['labels'] ?? null) ? $legacy['labels'] : [];
        $arDefault = self::defaultYearsBadgeForLocale('ar');
        $enDefault = self::defaultYearsBadgeForLocale('en');

        return [
            'ar' => [
                'count' => (int) ($legacy['count'] ?? $arDefault['count']),
                'suffix' => (string) ($legacy['suffix'] ?? $arDefault['suffix']),
                'label' => self::nonEmptyString($labels['ar'] ?? null, $arDefault['label']),
            ],
            'en' => [
                'count' => (int) ($legacy['count'] ?? $enDefault['count']),
                'suffix' => (string) ($legacy['suffix'] ?? $enDefault['suffix']),
                'label' => self::nonEmptyString($labels['en'] ?? null, $enDefault['label']),
            ],
        ];
    }

    private static function nonEmptyString(mixed $value, string $fallback): string
    {
        $text = trim((string) ($value ?? ''));

        return $text !== '' ? $text : $fallback;
    }
}
