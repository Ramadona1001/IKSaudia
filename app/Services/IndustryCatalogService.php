<?php

namespace App\Services;

use App\Models\Industry;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class IndustryCatalogService
{
    /** @return Collection<int, Industry> */
    public function featured(string $locale, int $limit = 6): Collection
    {
        return Cache::remember("industries.featured.{$locale}", 3600, function () use ($locale, $limit) {
            return Industry::query()
                ->published()
                ->where('is_featured', true)
                ->with(['translations' => fn ($q) => $q->where('locale', $locale)])
                ->orderBy('sort_order')
                ->limit($limit)
                ->get();
        });
    }

    /** @return Collection<int, Industry> */
    public function publishedList(string $locale): Collection
    {
        return Cache::remember("industries.list.{$locale}", 3600, function () use ($locale) {
            return Industry::query()
                ->published()
                ->with(['translations' => fn ($q) => $q->where('locale', $locale)])
                ->orderBy('sort_order')
                ->get();
        });
    }

    public function findPublishedBySlug(string $slug, string $locale): ?Industry
    {
        return Cache::remember(
            "industry.{$locale}.{$slug}",
            3600,
            fn () => Industry::query()
                ->published()
                ->whereHas('translations', fn ($q) => $q->where('locale', $locale)->where('slug', $slug))
                ->with([
                    'translations' => fn ($q) => $q->where('locale', $locale),
                    'seoMeta' => fn ($q) => $q->where('locale', $locale),
                    'services.translations' => fn ($q) => $q->where('locale', $locale),
                ])
                ->first()
        );
    }

    public function clearCache(): void
    {
        app(ContentCacheService::class)->forgetIndustries();

        foreach (config('locales.supported', ['ar', 'en']) as $locale) {
            Cache::forget("industries.list.{$locale}");
        }
    }
}
