<?php

namespace App\Support;

use App\Models\Industry;
use App\Models\PageTranslation;
use App\Models\Product;
use App\Models\Project;
use App\Models\Service;

final class SiteLinkCatalog
{
    public const CUSTOM = '__custom__';

    /**
     * @return array<string, array<string, string>>
     */
    public static function groupedOptions(): array
    {
        $groups = [
            'Main pages' => self::mainPages(),
            'Homepage sections' => self::homepageSections(),
            'Services' => self::services(),
            'Industries' => self::industries(),
            'Products' => self::products(),
            'Projects' => self::projects(),
            'CMS pages' => self::cmsPages(),
        ];

        return array_merge(
            array_filter($groups, fn (array $group): bool => $group !== []),
            [
                'Other' => [
                    self::CUSTOM => 'Custom URL…',
                ],
            ],
        );
    }

    /**
     * @param  array<string, mixed>  $row
     * @return array<string, mixed>
     */
    public static function hydrateLinkRow(array $row): array
    {
        $url = trim((string) ($row['url'] ?? ''));

        if ($url === '') {
            $row['url_destination'] = null;
            $row['url_custom'] = null;

            return $row;
        }

        if (self::isKnownPreset($url)) {
            $row['url_destination'] = $url;
            $row['url_custom'] = null;
        } else {
            $row['url_destination'] = self::CUSTOM;
            $row['url_custom'] = $url;
        }

        return $row;
    }

    /**
     * @param  array<string, mixed>  $footer
     * @return array<string, mixed>
     */
    public static function hydrateFooterLinkGroups(array $footer): array
    {
        foreach (['quick_links', 'service_links', 'industry_links', 'legal_links'] as $key) {
            $footer[$key] = collect($footer[$key] ?? [])
                ->map(fn ($row) => self::hydrateLinkRow(is_array($row) ? $row : []))
                ->values()
                ->all();
        }

        return $footer;
    }

    /**
     * @param  array<string, mixed>  $row
     */
    public static function resolveStoredUrl(array $row): string
    {
        $destination = trim((string) ($row['url_destination'] ?? ''));

        if ($destination === self::CUSTOM) {
            return trim((string) ($row['url_custom'] ?? $row['url'] ?? ''));
        }

        if ($destination !== '') {
            return $destination;
        }

        return trim((string) ($row['url'] ?? ''));
    }

    public static function isKnownPreset(string $url): bool
    {
        return in_array($url, self::presetValues(), true);
    }

    /**
     * @return list<string>
     */
    public static function presetValues(): array
    {
        return collect(self::groupedOptions())
            ->except('Other')
            ->flatMap(fn (array $group) => array_keys($group))
            ->values()
            ->all();
    }

    /**
     * @return array<string, string>
     */
    private static function mainPages(): array
    {
        return [
            'route:home' => 'Home',
            'route:about' => 'About',
            'route:services.index' => 'Services (all)',
            'route:industries.index' => 'Industries (all)',
            'route:products.index' => 'Products (all)',
            'route:projects.index' => 'Projects (all)',
            'route:clients' => 'Clients',
            'route:partners' => 'Partners',
            'route:faq' => 'FAQ',
            'route:contact' => 'Contact',
        ];
    }

    /**
     * @return array<string, string>
     */
    private static function homepageSections(): array
    {
        $sections = [
            'hero' => 'Hero',
            'about' => 'About',
            'foundation' => 'Mission, vision & values',
            'services' => 'Services',
            'industries' => 'Industries',
            'clients' => 'Clients',
            'partners' => 'Partners',
            'projects' => 'Projects',
            'certifications' => 'Certifications',
            'faq' => 'FAQ',
        ];

        $options = [];

        foreach ($sections as $id => $label) {
            $options['route:home#'.$id] = $label;
        }

        return $options;
    }

    /**
     * @return array<string, string>
     */
    private static function services(): array
    {
        $options = [];

        $services = Service::query()
            ->where('is_published', true)
            ->orderBy('sort_order')
            ->with('translations')
            ->get();

        foreach ($services as $service) {
            $slug = $service->translate('en')?->slug ?? $service->translate('ar')?->slug;

            if (! filled($slug)) {
                continue;
            }

            $title = $service->translate('en')?->title
                ?? $service->translate('ar')?->title
                ?? $slug;

            $options['route:services.show/'.$slug] = $title;
        }

        return $options;
    }

    /**
     * @return array<string, string>
     */
    private static function industries(): array
    {
        $options = [];

        $industries = Industry::query()
            ->where('is_published', true)
            ->orderBy('sort_order')
            ->with('translations')
            ->get();

        foreach ($industries as $industry) {
            $slug = $industry->translate('en')?->slug ?? $industry->translate('ar')?->slug;

            if (! filled($slug)) {
                continue;
            }

            $title = $industry->translate('en')?->title
                ?? $industry->translate('ar')?->title
                ?? $slug;

            $options['route:industries.show/'.$slug] = $title;
        }

        return $options;
    }

    /**
     * @return array<string, string>
     */
    private static function products(): array
    {
        $options = [];

        $products = Product::query()
            ->where('is_published', true)
            ->orderBy('sort_order')
            ->with('translations')
            ->get();

        foreach ($products as $product) {
            $slug = $product->translate('en')?->slug ?? $product->translate('ar')?->slug;

            if (! filled($slug)) {
                continue;
            }

            $title = $product->translate('en')?->title
                ?? $product->translate('ar')?->title
                ?? $slug;

            $options['route:products.show/'.$slug] = $title;
        }

        return $options;
    }

    /**
     * @return array<string, string>
     */
    private static function projects(): array
    {
        $options = [];

        $projects = Project::query()
            ->where('is_published', true)
            ->orderBy('sort_order')
            ->with('translations')
            ->get();

        foreach ($projects as $project) {
            $slug = $project->translate('en')?->slug ?? $project->translate('ar')?->slug;

            if (! filled($slug)) {
                continue;
            }

            $title = $project->translate('en')?->title
                ?? $project->translate('ar')?->title
                ?? $slug;

            $options['route:projects.show/'.$slug] = $title;
        }

        return $options;
    }

    /**
     * @return array<string, string>
     */
    private static function cmsPages(): array
    {
        return PageTranslation::query()
            ->where('locale', 'en')
            ->orderBy('title')
            ->pluck('title', 'slug')
            ->all();
    }
}
