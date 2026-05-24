<?php

namespace App\Services;

use App\Models\Page;
use Illuminate\Support\Facades\Cache;
use App\Services\ContentCacheService;

class PageService
{
    public function findPublishedBySlug(string $slug, string $locale): ?Page
    {
        return Cache::remember(
            "page.{$locale}.{$slug}",
            3600,
            fn () => Page::query()
                ->published()
                ->whereHas('translations', fn ($q) => $q->where('locale', $locale)->where('slug', $slug))
                ->with([
                    'translations' => fn ($q) => $q->where('locale', $locale),
                    'seoMeta' => fn ($q) => $q->where('locale', $locale),
                    'blocks.translations' => fn ($q) => $q->where('locale', $locale),
                ])
                ->first()
        );
    }

    public function clearCache(?string $locale = null, ?string $slug = null): void
    {
        $cache = app(ContentCacheService::class);

        if ($locale && $slug) {
            $cache->forgetPage($locale, $slug);

            return;
        }

        foreach (['ar', 'en'] as $code) {
            Page::query()
                ->published()
                ->with(['translations' => fn ($q) => $q->where('locale', $code)])
                ->each(function (Page $page) use ($cache, $code): void {
                    $translation = $page->translations->firstWhere('locale', $code);
                    if ($translation) {
                        $cache->forgetPage($code, $translation->slug);
                    }
                });
        }
    }
}
