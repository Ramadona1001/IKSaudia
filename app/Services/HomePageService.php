<?php

namespace App\Services;

use App\Models\HomeSection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class HomePageService
{
    public function __construct(
        protected LocaleService $locales,
    ) {}

    /** @return Collection<int, HomeSection> */
    public function sections(?string $locale = null): Collection
    {
        $locale ??= app()->getLocale();

        return Cache::remember("home.sections.{$locale}", 3600, function () use ($locale) {
            return HomeSection::query()
                ->where('is_active', true)
                ->with([
                    'translations' => fn ($q) => $q->where('locale', $locale),
                    'items' => fn ($q) => $q
                        ->where('is_active', true)
                        ->orderBy('sort_order')
                        ->with(['translations' => fn ($q) => $q->where('locale', $locale)]),
                ])
                ->orderBy('sort_order')
                ->get();
        });
    }

    public function clearCache(): void
    {
        foreach ($this->locales->active() as $locale) {
            Cache::forget("home.sections.{$locale->code}");
        }
    }
}
