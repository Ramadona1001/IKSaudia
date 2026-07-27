<?php

namespace App\Services;

use App\Models\Partner;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class PartnerCatalogService
{
    /** @return Collection<int, Partner> */
    public function publishedList(?string $locale = null): Collection
    {
        $locale ??= app()->getLocale();

        return Cache::remember("partners.list.{$locale}", 3600, function () use ($locale) {
            return Partner::query()
                ->where('is_published', true)
                ->with(['translations' => fn ($q) => $q->where('locale', $locale)])
                ->orderBy('sort_order')
                ->get();
        });
    }

    public function clearCache(): void
    {
        foreach (config('locales.supported', ['ar', 'en']) as $locale) {
            Cache::forget("partners.list.{$locale}");
        }
    }
}
