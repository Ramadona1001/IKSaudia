<?php

namespace App\Support;

final class FoundationSection
{
    /**
     * @return array{eyebrow: string, title: string, highlight: string}
     */
    public static function headingForLocale(?array $settings, string $locale): array
    {
        $settings = self::normalizeSettings($settings);
        $row = is_array($settings['heading'][$locale] ?? null) ? $settings['heading'][$locale] : [];

        return [
            'eyebrow' => self::nonEmptyString($row['eyebrow'] ?? null, __('front.about.foundation_eyebrow', [], $locale)),
            'title' => self::nonEmptyString($row['title'] ?? null, __('front.about.foundation_title', [], $locale)),
            'highlight' => self::nonEmptyString($row['highlight'] ?? null, __('front.about.foundation_highlight', [], $locale)),
        ];
    }

    /**
     * @return list<array{key: string, title: string, description: string, icon: string, variant: string}>
     */
    public static function cardsForLocale(?array $settings, string $locale): array
    {
        $settings = self::normalizeSettings($settings);
        $cards = [];

        foreach (self::cardDefinitions($locale) as $key => $defaults) {
            $row = is_array($settings[$key][$locale] ?? null) ? $settings[$key][$locale] : [];

            $cards[] = [
                'key' => $key,
                'title' => self::nonEmptyString($row['title'] ?? null, $defaults['title']),
                'description' => self::nonEmptyString($row['description'] ?? null, $defaults['description']),
                'icon' => $defaults['icon'],
                'variant' => $defaults['variant'],
            ];
        }

        return $cards;
    }

    /**
     * @return array<string, mixed>
     */
    public static function defaultSettings(): array
    {
        $settings = ['heading' => []];

        foreach (['ar', 'en'] as $locale) {
            $settings['heading'][$locale] = [
                'eyebrow' => __('front.about.foundation_eyebrow', [], $locale),
                'title' => __('front.about.foundation_title', [], $locale),
                'highlight' => __('front.about.foundation_highlight', [], $locale),
            ];

            foreach (self::cardDefinitions($locale) as $key => $defaults) {
                $settings[$key][$locale] = [
                    'title' => $defaults['title'],
                    'description' => $defaults['description'],
                ];
            }
        }

        return $settings;
    }

    /**
     * @param  array<string, mixed>|null  $settings
     * @return array<string, mixed>
     */
    public static function normalizeSettings(?array $settings): array
    {
        $defaults = self::defaultSettings();
        $settings ??= [];

        foreach (['heading', 'mission', 'vision', 'values'] as $group) {
            foreach (['ar', 'en'] as $locale) {
                $settings[$group][$locale] = array_merge(
                    $defaults[$group][$locale] ?? [],
                    is_array($settings[$group][$locale] ?? null) ? $settings[$group][$locale] : [],
                );
            }
        }

        return $settings;
    }

    /**
     * @return array<string, array{title: string, description: string, icon: string, variant: string}>
     */
    private static function cardDefinitions(string $locale): array
    {
        return [
            'mission' => [
                'title' => __('front.home.about.mission_title', [], $locale),
                'description' => __('front.home.about.mission_desc', [], $locale),
                'icon' => 'bi-bullseye',
                'variant' => 'mission',
            ],
            'vision' => [
                'title' => __('front.home.about.vision_title', [], $locale),
                'description' => __('front.home.about.vision_desc', [], $locale),
                'icon' => 'bi-eye-fill',
                'variant' => 'vision',
            ],
            'values' => [
                'title' => __('front.about.values_title', [], $locale),
                'description' => __('front.about.values_desc', [], $locale),
                'icon' => 'bi-stars',
                'variant' => 'values',
            ],
        ];
    }

    private static function nonEmptyString(mixed $value, string $fallback): string
    {
        $text = trim((string) ($value ?? ''));

        return $text !== '' ? $text : $fallback;
    }
}
