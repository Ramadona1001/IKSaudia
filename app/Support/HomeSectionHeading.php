<?php

namespace App\Support;

use App\Models\HomeSection;

final class HomeSectionHeading
{
    /**
     * @return array<string, string>
     */
    public static function sectionLabels(): array
    {
        return [
            'about' => 'About section',
            'foundation' => 'Mission, vision & values',
            'services' => 'Services section',
            'products' => 'Products section',
            'industries' => 'Industries section',
            'clients' => 'Clients section',
            'partners' => 'Partners section',
            'projects' => 'Projects section',
            'certifications' => 'Certifications section',
            'faq' => 'FAQ section',
            'contact' => 'Contact section',
        ];
    }

    public static function breadcrumbLabel(string $key, ?string $locale = null): string
    {
        $locale ??= app()->getLocale();
        $heading = self::resolve($key, $locale);
        $label = trim($heading['title'].$heading['highlight']);

        if ($label !== '') {
            return $label;
        }

        return self::fallbackBreadcrumbLabel($key, $locale);
    }

    /**
     * @return array{eyebrow: string, title: string, highlight: string, description: string}
     */
    public static function resolve(string $key, ?string $locale = null, ?HomeSection $section = null): array
    {
        $locale ??= app()->getLocale();

        $heading = self::defaults($key, $locale);
        $heading = self::merge($heading, self::cmsOverrides($key, $section, $locale));
        $heading = self::merge($heading, self::fromSettings($key, $locale));

        if ($key === 'about' && filled($heading['title']) && ! self::settingsFieldFilled($key, 'highlight', $locale)) {
            $cmsTitle = self::cmsOverrides($key, $section, $locale)['title'] ?? '';

            if (filled($cmsTitle) && $heading['title'] === $cmsTitle) {
                $heading['highlight'] = '';
            }
        }

        return $heading;
    }

    /**
     * @return array<string, array<string, array<string, string>>>
     */
    public static function defaultSettingsPayload(): array
    {
        $payload = [];

        foreach (array_keys(self::sectionLabels()) as $key) {
            $payload[$key] = [];

            foreach (['ar', 'en'] as $locale) {
                $heading = self::defaults($key, $locale);
                $payload[$key]['eyebrow'][$locale] = $heading['eyebrow'];
                $payload[$key]['title'][$locale] = $heading['title'];
                $payload[$key]['highlight'][$locale] = $heading['highlight'];
                $payload[$key]['description'][$locale] = $heading['description'];
            }
        }

        return $payload;
    }

    /**
     * @return array{eyebrow: string, title: string, highlight: string, description: string}
     */
    private static function defaults(string $key, string $locale): array
    {
        return match ($key) {
            'about' => [
                'eyebrow' => __('front.home.about.eyebrow', [], $locale),
                'title' => __('front.home.about.title', [], $locale),
                'highlight' => __('front.home.about.highlight', [], $locale),
                'description' => __('front.home.about.desc', [], $locale),
            ],
            'foundation' => [
                'eyebrow' => __('front.about.foundation_eyebrow', [], $locale),
                'title' => __('front.about.foundation_title', [], $locale),
                'highlight' => __('front.about.foundation_highlight', [], $locale),
                'description' => '',
            ],
            'services' => [
                'eyebrow' => __('front.services.tag', [], $locale),
                'title' => __('front.services.title', [], $locale),
                'highlight' => __('front.services.highlight', [], $locale),
                'description' => __('front.services.subtitle', [], $locale),
            ],
            'products' => [
                'eyebrow' => __('front.products.tag', [], $locale),
                'title' => __('front.products.title', [], $locale),
                'highlight' => __('front.products.highlight', [], $locale),
                'description' => __('front.products.subtitle', [], $locale),
            ],
            'industries' => [
                'eyebrow' => __('front.home.industries.eyebrow', [], $locale),
                'title' => __('front.home.industries.title', [], $locale),
                'highlight' => __('front.home.industries.highlight', [], $locale),
                'description' => __('front.home.industries.desc', [], $locale),
            ],
            'clients' => [
                'eyebrow' => __('front.home.clients.eyebrow', [], $locale),
                'title' => __('front.home.clients.title', [], $locale),
                'highlight' => __('front.home.clients.highlight', [], $locale),
                'description' => __('front.home.clients.desc', [], $locale),
            ],
            'partners' => [
                'eyebrow' => __('front.home.partners.eyebrow', [], $locale),
                'title' => __('front.home.partners.title', [], $locale),
                'highlight' => __('front.home.partners.highlight', [], $locale),
                'description' => __('front.home.partners.desc', [], $locale),
            ],
            'projects' => [
                'eyebrow' => __('front.home.projects.eyebrow', [], $locale),
                'title' => __('front.home.projects.title', [], $locale),
                'highlight' => __('front.home.projects.highlight', [], $locale),
                'description' => __('front.home.projects.desc', [], $locale),
            ],
            'certifications' => [
                'eyebrow' => __('front.home.certs.eyebrow', [], $locale),
                'title' => __('front.home.certs.title', [], $locale),
                'highlight' => __('front.home.certs.highlight', [], $locale),
                'description' => __('front.home.certs.desc', [], $locale),
            ],
            'faq' => [
                'eyebrow' => __('front.home.faq.eyebrow', [], $locale),
                'title' => __('front.home.faq.title', [], $locale),
                'highlight' => __('front.home.faq.highlight', [], $locale),
                'description' => __('front.home.faq.desc', [], $locale),
            ],
            'contact' => [
                'eyebrow' => __('front.contact.tag', [], $locale),
                'title' => __('front.contact.title', [], $locale),
                'highlight' => __('front.contact.highlight', [], $locale),
                'description' => __('front.contact.subtitle', [], $locale),
            ],
            default => [
                'eyebrow' => '',
                'title' => '',
                'highlight' => '',
                'description' => '',
            ],
        };
    }

    /**
     * @return array{eyebrow?: string, title?: string, highlight?: string, description?: string}
     */
    private static function cmsOverrides(string $key, ?HomeSection $section, string $locale): array
    {
        if (! $section) {
            return [];
        }

        if ($key === 'about') {
            $translation = $section->translate($locale);

            return array_filter([
                'eyebrow' => filled($translation?->subtitle) ? (string) $translation->subtitle : null,
                'title' => filled($translation?->title) ? (string) $translation->title : null,
                'description' => filled($translation?->content) ? (string) $translation->bodyText() : null,
            ], fn ($value) => filled($value));
        }

        if ($key === 'foundation') {
            $heading = FoundationSection::headingForSection($section, $locale);

            return [
                'eyebrow' => $heading['eyebrow'],
                'title' => $heading['title'],
                'highlight' => $heading['highlight'],
            ];
        }

        return [];
    }

    /**
     * @return array{eyebrow?: string, title?: string, highlight?: string, description?: string}
     */
    private static function fromSettings(string $key, string $locale): array
    {
        $stored = setting('homepage.section_headings', []);

        if (! is_array($stored) || ! is_array($stored[$key] ?? null)) {
            return [];
        }

        $row = $stored[$key];
        $values = [];

        foreach (['eyebrow', 'title', 'highlight', 'description'] as $field) {
            $localized = $row[$field] ?? null;

            if (is_array($localized)) {
                $text = trim((string) ($localized[$locale] ?? $localized['en'] ?? $localized['ar'] ?? ''));
            } else {
                $text = trim((string) ($localized ?? ''));
            }

            if ($text !== '') {
                $values[$field] = $text;
            }
        }

        return $values;
    }

    private static function settingsFieldFilled(string $key, string $field, string $locale): bool
    {
        $stored = setting('homepage.section_headings', []);

        if (! is_array($stored[$key][$field] ?? null)) {
            return false;
        }

        return filled($stored[$key][$field][$locale] ?? null);
    }

    /**
     * @param  array{eyebrow: string, title: string, highlight: string, description: string}  $base
     * @param  array{eyebrow?: string, title?: string, highlight?: string, description?: string}  $overlay
     * @return array{eyebrow: string, title: string, highlight: string, description: string}
     */
    private static function merge(array $base, array $overlay): array
    {
        foreach (['eyebrow', 'title', 'highlight', 'description'] as $field) {
            if (filled($overlay[$field] ?? null)) {
                $base[$field] = (string) $overlay[$field];
            }
        }

        return $base;
    }

    private static function fallbackBreadcrumbLabel(string $key, string $locale): string
    {
        return match ($key) {
            'about' => __('front.about.breadcrumb', [], $locale),
            'foundation' => trim(__('front.about.foundation_title', [], $locale).__('front.about.foundation_highlight', [], $locale)),
            'services' => __('front.services.breadcrumb', [], $locale),
            'products' => __('front.products.breadcrumb', [], $locale),
            'industries' => __('front.industries.breadcrumb', [], $locale),
            'clients' => __('front.clients.breadcrumb', [], $locale),
            'partners' => __('front.partners.breadcrumb', [], $locale),
            'projects' => __('navigation.projects', [], $locale),
            'certifications' => trim(__('front.home.certs.title', [], $locale).__('front.home.certs.highlight', [], $locale)),
            'faq' => __('front.faq.breadcrumb', [], $locale),
            'contact' => __('front.contact.breadcrumb', [], $locale),
            default => '',
        };
    }
}
