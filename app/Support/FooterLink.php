<?php

namespace App\Support;

use App\Models\Industry;
use App\Models\Service;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;

final class FooterLink
{
    public static function url(string $url, ?string $locale = null): string
    {
        $locale ??= app()->getLocale();
        $url = trim($url);

        if ($url === '' || $url === '#') {
            return '#';
        }

        if (str_starts_with($url, 'http://') || str_starts_with($url, 'https://')) {
            return $url;
        }

        if (str_starts_with($url, '/')) {
            return url($url);
        }

        if (str_starts_with($url, 'route:')) {
            return self::routeUrl(substr($url, 6), $locale);
        }

        if (Route::has($url)) {
            return self::routeUrl($url, $locale);
        }

        return route('page.show', [$locale, ltrim($url, '/')]);
    }

    public static function label(array $link, ?string $locale = null): string
    {
        $locale ??= app()->getLocale();

        if ($locale === 'ar') {
            return (string) ($link['label_ar'] ?? $link['label_en'] ?? '');
        }

        return (string) ($link['label_en'] ?? $link['label_ar'] ?? '');
    }

    /**
     * @return list<array<string, mixed>>
     */
    public static function visibleGroup(string $groupKey, ?string $locale = null, ?array $fallback = null): array
    {
        $locale ??= app()->getLocale();

        $links = collect(setting("footer.{$groupKey}", []))
            ->filter(fn ($link) => is_array($link) && ($link['is_visible'] ?? true))
            ->sortBy(fn (array $link) => (int) ($link['sort_order'] ?? 0))
            ->values();

        if ($links->isNotEmpty()) {
            return $links->all();
        }

        return $fallback ?? [];
    }

    /**
     * @return list<array<string, mixed>>
     */
    public static function defaultQuickLinks(): array
    {
        return collect(config('front-nav.footer.company', []))
            ->values()
            ->map(fn (array $link, int $index): array => [
                'label_en' => __($link['label'], [], 'en'),
                'label_ar' => __($link['label'], [], 'ar'),
                'url' => 'route:'.$link['route'],
                'is_visible' => true,
                'sort_order' => $index,
            ])
            ->all();
    }

    /**
     * @param  Collection<int, mixed>  $services
     * @return list<array<string, mixed>>
     */
    public static function linksFromServices(Collection $services, ?string $locale = null): array
    {
        $locale ??= app()->getLocale();

        return $services
            ->map(function ($service, int $index) use ($locale): ?array {
                $translation = $service->translate($locale);

                if (! $translation || ! filled($translation->slug ?? null)) {
                    return null;
                }

                $english = $service->translate('en');
                $arabic = $service->translate('ar');

                return [
                    'label_en' => $english?->title ?? $translation->title,
                    'label_ar' => $arabic?->title ?? $translation->title,
                    'url' => 'route:services.show/'.$translation->slug,
                    'is_visible' => true,
                    'sort_order' => $index,
                ];
            })
            ->filter()
            ->values()
            ->all();
    }

    /**
     * @param  Collection<int, mixed>  $industries
     * @return list<array<string, mixed>>
     */
    public static function linksFromIndustries(Collection $industries, ?string $locale = null): array
    {
        $locale ??= app()->getLocale();

        return $industries
            ->map(function ($industry, int $index) use ($locale): ?array {
                $translation = $industry->translate($locale);

                if (! $translation || ! filled($translation->slug ?? null)) {
                    return null;
                }

                $english = $industry->translate('en');
                $arabic = $industry->translate('ar');

                return [
                    'label_en' => $english?->title ?? $translation->title,
                    'label_ar' => $arabic?->title ?? $translation->title,
                    'url' => 'route:industries.show/'.$translation->slug,
                    'is_visible' => true,
                    'sort_order' => $index,
                ];
            })
            ->filter()
            ->values()
            ->all();
    }

    /**
     * Footer link groups for website settings seeders.
     *
     * @return array{
     *     quick_links: list<array<string, mixed>>,
     *     service_links: list<array<string, mixed>>,
     *     industry_links: list<array<string, mixed>>,
     *     legal_links: list<array<string, mixed>>
     * }
     */
    public static function linkGroupsPayload(): array
    {
        $services = Service::query()
            ->where('is_published', true)
            ->orderBy('sort_order')
            ->with('translations')
            ->get();

        $industries = Industry::query()
            ->where('is_published', true)
            ->orderBy('sort_order')
            ->with('translations')
            ->get();

        $serviceLinks = self::linksFromServices($services);
        $industryLinks = self::linksFromIndustries($industries);

        return [
            'quick_links' => self::defaultQuickLinks(),
            'service_links' => $serviceLinks !== [] ? $serviceLinks : self::defaultServiceLinks(),
            'industry_links' => $industryLinks !== [] ? $industryLinks : self::defaultIndustryLinks(),
            'legal_links' => self::defaultLegalLinks(),
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    public static function defaultLegalLinks(): array
    {
        return [
            [
                'label_en' => __('common.privacy', [], 'en'),
                'label_ar' => __('common.privacy', [], 'ar'),
                'url' => 'privacy-policy',
                'is_visible' => true,
                'sort_order' => 0,
            ],
            [
                'label_en' => __('common.terms', [], 'en'),
                'label_ar' => __('common.terms', [], 'ar'),
                'url' => 'terms-of-use',
                'is_visible' => true,
                'sort_order' => 1,
            ],
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    public static function defaultServiceLinks(): array
    {
        return [
            [
                'label_en' => __('navigation.services', [], 'en'),
                'label_ar' => __('navigation.services', [], 'ar'),
                'url' => 'route:services.index',
                'is_visible' => true,
                'sort_order' => 0,
            ],
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    public static function defaultIndustryLinks(): array
    {
        return [
            [
                'label_en' => __('navigation.industries', [], 'en'),
                'label_ar' => __('navigation.industries', [], 'ar'),
                'url' => 'route:industries.index',
                'is_visible' => true,
                'sort_order' => 0,
            ],
        ];
    }

    private static function routeUrl(string $route, string $locale): string
    {
        $route = trim($route);

        if (str_starts_with($route, 'services.show/')) {
            $slug = substr($route, strlen('services.show/'));

            return route('services.show', [$locale, $slug]);
        }

        if (str_starts_with($route, 'projects.show/')) {
            $slug = substr($route, strlen('projects.show/'));

            return route('projects.show', [$locale, $slug]);
        }

        if (str_starts_with($route, 'industries.show/')) {
            $slug = substr($route, strlen('industries.show/'));

            return route('industries.show', [$locale, $slug]);
        }

        if (in_array($route, ['products.index', 'products.show'], true)) {
            return route($route, $route === 'products.show' ? [''] : []);
        }

        if ($route === 'page.show') {
            return route('page.show', [$locale, 'about-us']);
        }

        if (Route::has($route)) {
            return route($route, [$locale]);
        }

        return '#';
    }
}
