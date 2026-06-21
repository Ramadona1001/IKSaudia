<?php

namespace App\Support;

use App\Models\HomeSection;
use App\Models\HomeSectionTranslation;

final class FoundationSection
{
    /**
     * @return array{eyebrow: string, title: string, highlight: string}
     */
    public static function headingForSection(?HomeSection $section, string $locale): array
    {
        $payload = self::localePayload($section, $locale);
        $row = is_array($payload['heading'] ?? null) ? $payload['heading'] : [];

        return [
            'eyebrow' => self::nonEmptyString($row['eyebrow'] ?? null, __('front.about.foundation_eyebrow', [], $locale)),
            'title' => self::nonEmptyString($row['title'] ?? null, __('front.about.foundation_title', [], $locale)),
            'highlight' => self::nonEmptyString($row['highlight'] ?? null, __('front.about.foundation_highlight', [], $locale)),
        ];
    }

    /**
     * @return list<array{key: string, title: string, description: string, icon: string, variant: string}>
     */
    public static function cardsForSection(?HomeSection $section, string $locale): array
    {
        $payload = self::localePayload($section, $locale);
        $cards = [];

        foreach (self::cardDefinitions($locale) as $key => $defaults) {
            $row = is_array($payload[$key] ?? null) ? $payload[$key] : [];

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
     * @return array{heading: array<string, string>, mission: array<string, string>, vision: array<string, string>, values: array<string, string>}
     */
    public static function localePayload(?HomeSection $section, string $locale): array
    {
        if ($section) {
            $translation = $section->relationLoaded('translations')
                ? $section->translations->firstWhere('locale', $locale)
                : $section->translationFor($locale);

            $decoded = self::decodePayload(self::translationRawContent($translation));
            if ($decoded !== null) {
                return $decoded;
            }
        }

        $settings = self::normalizeSettings(is_array($section?->settings) ? $section->settings : []);

        return self::localePayloadFromSettings($settings, $locale);
    }

    /**
     * @param  array<string, mixed>  $settings
     * @return array{heading: array<string, string>, mission: array<string, string>, vision: array<string, string>, values: array<string, string>}
     */
    public static function localePayloadFromSettings(array $settings, string $locale): array
    {
        $settings = self::normalizeSettings($settings);

        return [
            'heading' => $settings['heading'][$locale] ?? [],
            'mission' => $settings['mission'][$locale] ?? [],
            'vision' => $settings['vision'][$locale] ?? [],
            'values' => $settings['values'][$locale] ?? [],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function defaultSettings(): array
    {
        $settings = ['heading' => [], 'mission' => [], 'vision' => [], 'values' => []];

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
     * @param  array{heading: array<string, string>, mission: array<string, string>, vision: array<string, string>, values: array<string, string>}  $payload
     */
    public static function encodePayload(array $payload): string
    {
        return json_encode($payload, JSON_UNESCAPED_UNICODE);
    }

    /**
     * @return array{heading: array<string, string>, mission: array<string, string>, vision: array<string, string>, values: array<string, string>}|null
     */
    public static function decodePayload(mixed $raw): ?array
    {
        if (! is_string($raw) || trim($raw) === '') {
            return null;
        }

        $decoded = json_decode($raw, true);
        if (! is_array($decoded) || ! isset($decoded['mission'])) {
            return null;
        }

        return [
            'heading' => is_array($decoded['heading'] ?? null) ? $decoded['heading'] : [],
            'mission' => is_array($decoded['mission'] ?? null) ? $decoded['mission'] : [],
            'vision' => is_array($decoded['vision'] ?? null) ? $decoded['vision'] : [],
            'values' => is_array($decoded['values'] ?? null) ? $decoded['values'] : [],
        ];
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

    private static function translationRawContent(?HomeSectionTranslation $translation): mixed
    {
        if (! $translation) {
            return null;
        }

        return $translation->getAttributes()['content'] ?? null;
    }
}
