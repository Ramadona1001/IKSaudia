<?php

namespace App\Services;

use App\Models\Certification;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class CertificationCatalogService
{
    /** @return Collection<int, Certification> */
    public function featured(string $locale, int $limit = 8): Collection
    {
        return Cache::remember("certifications.featured.{$locale}", 3600, function () use ($locale, $limit) {
            return Certification::query()
                ->published()
                ->where('is_featured', true)
                ->with(['translations' => fn ($q) => $q->where('locale', $locale)])
                ->orderBy('sort_order')
                ->limit($limit)
                ->get();
        });
    }

    /** @return Collection<int, Certification> */
    public function publishedList(string $locale): Collection
    {
        return Cache::remember("certifications.list.{$locale}", 3600, function () use ($locale) {
            return Certification::query()
                ->published()
                ->with(['translations' => fn ($q) => $q->where('locale', $locale)])
                ->orderBy('sort_order')
                ->get();
        });
    }

    public function clearCache(): void
    {
        app(ContentCacheService::class)->forgetCertifications();

        foreach (config('locales.supported', ['ar', 'en']) as $locale) {
            Cache::forget("certifications.list.{$locale}");
        }
    }
}
