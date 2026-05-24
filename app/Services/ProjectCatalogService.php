<?php

namespace App\Services;

use App\Models\Project;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class ProjectCatalogService
{
    public function publishedList(string $locale, int $perPage = 12): LengthAwarePaginator
    {
        return Project::query()
            ->published()
            ->with(['translations' => fn ($q) => $q->where('locale', $locale)])
            ->orderByDesc('year')
            ->orderBy('sort_order')
            ->paginate($perPage);
    }

    /** @return Collection<int, Project> */
    public function featured(string $locale, int $limit = 8): Collection
    {
        return Cache::remember("projects.featured.{$locale}", 3600, function () use ($locale, $limit) {
            return Project::query()
                ->published()
                ->where('is_featured', true)
                ->with(['translations' => fn ($q) => $q->where('locale', $locale)])
                ->orderBy('sort_order')
                ->limit($limit)
                ->get();
        });
    }

    public function findPublishedBySlug(string $slug, string $locale): ?Project
    {
        return Cache::remember(
            "project.{$locale}.{$slug}",
            3600,
            fn () => Project::query()
                ->published()
                ->whereHas('translations', fn ($q) => $q->where('locale', $locale)->where('slug', $slug))
                ->with([
                    'translations' => fn ($q) => $q->where('locale', $locale),
                    'seoMeta' => fn ($q) => $q->where('locale', $locale),
                    'services.translations' => fn ($q) => $q->where('locale', $locale),
                    'industries.translations' => fn ($q) => $q->where('locale', $locale),
                ])
                ->first()
        );
    }

    public function clearCache(): void
    {
        app(ContentCacheService::class)->forgetProjects();
    }
}
