<?php

namespace App\Services;

use App\Models\Industry;
use App\Models\NewsPost;
use App\Models\Project;
use App\Models\Service;
use App\Support\BootstrapIcon;
use Illuminate\Support\Str;

class SiteSearchService
{
    private const MIN_LENGTH = 2;

    private const PER_TYPE = 5;

    /**
     * @return list<array{type: string, title: string, subtitle: string, url: string, icon: string}>
     */
    public function search(string $locale, string $query): array
    {
        $query = trim($query);

        if (mb_strlen($query) < self::MIN_LENGTH) {
            return [];
        }

        $term = '%'.$this->escapeLike($query).'%';

        return array_merge(
            $this->searchServices($locale, $term),
            $this->searchIndustries($locale, $term),
            $this->searchProjects($locale, $term),
            $this->searchNews($locale, $term),
        );
    }

    /**
     * @return list<array{type: string, title: string, subtitle: string, url: string, icon: string}>
     */
    private function searchServices(string $locale, string $term): array
    {
        return Service::query()
            ->published()
            ->whereHas('translations', function ($query) use ($locale, $term): void {
                $query->where('locale', $locale)
                    ->where(function ($query) use ($term): void {
                        $query->where('title', 'like', $term)
                            ->orWhere('summary', 'like', $term)
                            ->orWhere('body', 'like', $term);
                    });
            })
            ->with(['translations' => fn ($query) => $query->where('locale', $locale)])
            ->orderBy('sort_order')
            ->limit(self::PER_TYPE)
            ->get()
            ->map(function (Service $service) use ($locale): array {
                $translation = $service->translate($locale);

                return $this->result(
                    'service',
                    $translation?->title ?? '',
                    $translation?->summary ?? '',
                    route('services.show', [$locale, $translation?->slug ?? '']),
                    BootstrapIcon::classes($service->icon, 'bi-gear-fill'),
                );
            })
            ->filter(fn (array $row): bool => filled($row['title']) && filled($row['url']))
            ->values()
            ->all();
    }

    /**
     * @return list<array{type: string, title: string, subtitle: string, url: string, icon: string}>
     */
    private function searchIndustries(string $locale, string $term): array
    {
        return Industry::query()
            ->published()
            ->whereHas('translations', function ($query) use ($locale, $term): void {
                $query->where('locale', $locale)
                    ->where(function ($query) use ($term): void {
                        $query->where('title', 'like', $term)
                            ->orWhere('summary', 'like', $term)
                            ->orWhere('body', 'like', $term);
                    });
            })
            ->with(['translations' => fn ($query) => $query->where('locale', $locale)])
            ->orderBy('sort_order')
            ->limit(self::PER_TYPE)
            ->get()
            ->map(function (Industry $industry) use ($locale): array {
                $translation = $industry->translate($locale);

                return $this->result(
                    'industry',
                    $translation?->title ?? '',
                    $translation?->summary ?? '',
                    route('industries.show', [$locale, $translation?->slug ?? '']),
                    BootstrapIcon::classes($industry->icon, 'bi-grid-3x3-gap-fill'),
                );
            })
            ->filter(fn (array $row): bool => filled($row['title']) && filled($row['url']))
            ->values()
            ->all();
    }

    /**
     * @return list<array{type: string, title: string, subtitle: string, url: string, icon: string}>
     */
    private function searchProjects(string $locale, string $term): array
    {
        return Project::query()
            ->published()
            ->whereHas('translations', function ($query) use ($locale, $term): void {
                $query->where('locale', $locale)
                    ->where(function ($query) use ($term): void {
                        $query->where('title', 'like', $term)
                            ->orWhere('summary', 'like', $term)
                            ->orWhere('body', 'like', $term);
                    });
            })
            ->with(['translations' => fn ($query) => $query->where('locale', $locale)])
            ->orderByDesc('year')
            ->orderBy('sort_order')
            ->limit(self::PER_TYPE)
            ->get()
            ->map(function (Project $project) use ($locale): array {
                $translation = $project->translate($locale);

                return $this->result(
                    'project',
                    $translation?->title ?? '',
                    $translation?->summary ?? '',
                    route('projects.show', [$locale, $translation?->slug ?? '']),
                    'bi bi-kanban-fill',
                );
            })
            ->filter(fn (array $row): bool => filled($row['title']) && filled($row['url']))
            ->values()
            ->all();
    }

    /**
     * @return list<array{type: string, title: string, subtitle: string, url: string, icon: string}>
     */
    private function searchNews(string $locale, string $term): array
    {
        return NewsPost::query()
            ->published()
            ->whereHas('translations', function ($query) use ($locale, $term): void {
                $query->where('locale', $locale)
                    ->where(function ($query) use ($term): void {
                        $query->where('title', 'like', $term)
                            ->orWhere('excerpt', 'like', $term)
                            ->orWhere('body', 'like', $term);
                    });
            })
            ->with(['translations' => fn ($query) => $query->where('locale', $locale)])
            ->orderByDesc('published_at')
            ->limit(self::PER_TYPE)
            ->get()
            ->map(function (NewsPost $post) use ($locale): array {
                $translation = $post->translate($locale);

                return $this->result(
                    'news',
                    $translation?->title ?? '',
                    $translation?->excerpt ?? '',
                    route('news.show', [$locale, $translation?->slug ?? '']),
                    'bi bi-newspaper',
                );
            })
            ->filter(fn (array $row): bool => filled($row['title']) && filled($row['url']))
            ->values()
            ->all();
    }

    /**
     * @return array{type: string, title: string, subtitle: string, url: string, icon: string}
     */
    private function result(string $type, string $title, string $subtitle, string $url, string $icon): array
    {
        return [
            'type' => $type,
            'title' => $title,
            'subtitle' => Str::limit(trim($subtitle), 120),
            'url' => $url,
            'icon' => $icon,
        ];
    }

    private function escapeLike(string $value): string
    {
        return addcslashes($value, '%_\\');
    }
}
