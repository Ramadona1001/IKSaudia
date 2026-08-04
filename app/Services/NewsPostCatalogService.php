<?php

namespace App\Services;

use App\Models\NewsPost;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;

class NewsPostCatalogService
{
    public function publishedList(string $locale, int $perPage = 12): LengthAwarePaginator
    {
        return NewsPost::query()
            ->published()
            ->whereHas('translations', fn ($query) => $query->whereNotNull('title')->where('title', '!=', ''))
            ->with(['translations' => fn ($query) => $query->where('locale', $locale)])
            ->orderByDesc('published_at')
            ->paginate($perPage);
    }

    public function findPublishedBySlug(string $slug, string $locale): ?NewsPost
    {
        return Cache::remember(
            "news.{$locale}.{$slug}",
            3600,
            fn () => NewsPost::query()
                ->published()
                ->whereHas('translations', fn ($query) => $query->where('locale', $locale)->where('slug', $slug))
                ->with([
                    'translations' => fn ($query) => $query->where('locale', $locale),
                    'seoMeta' => fn ($query) => $query->where('locale', $locale),
                ])
                ->first()
        );
    }
}
