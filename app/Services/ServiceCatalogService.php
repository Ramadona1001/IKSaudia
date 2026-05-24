<?php

namespace App\Services;

use App\Models\Service;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class ServiceCatalogService
{
    public function publishedList(string $locale, int $perPage = 12): LengthAwarePaginator
    {
        return Service::query()
            ->published()
            ->with(['translations' => fn ($q) => $q->where('locale', $locale)])
            ->orderBy('sort_order')
            ->paginate($perPage);
    }

    /** @return Collection<int, Service> */
    public function featured(string $locale, int $limit = 6): Collection
    {
        return Cache::remember("services.featured.{$locale}", 3600, function () use ($locale, $limit) {
            return Service::query()
                ->published()
                ->where('is_featured', true)
                ->with(['translations' => fn ($q) => $q->where('locale', $locale)])
                ->orderBy('sort_order')
                ->limit($limit)
                ->get();
        });
    }

    public function findPublishedBySlug(string $slug, string $locale): ?Service
    {
        return Cache::remember(
            "service.{$locale}.{$slug}",
            3600,
            fn () => Service::query()
                ->published()
                ->whereHas('translations', fn ($q) => $q->where('locale', $locale)->where('slug', $slug))
                ->with([
                    'translations' => fn ($q) => $q->where('locale', $locale),
                    'seoMeta' => fn ($q) => $q->where('locale', $locale),
                    'industries.translations' => fn ($q) => $q->where('locale', $locale),
                ])
                ->first()
        );
    }

    public function clearCache(): void
    {
        app(ContentCacheService::class)->forgetServices();
    }
}
