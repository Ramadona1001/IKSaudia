<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class ProductCatalogService
{
    /** @return Collection<int, Product> */
    public function rootPublished(string $locale): Collection
    {
        return Cache::remember("products.root.{$locale}", 3600, function () use ($locale) {
            return Product::query()
                ->published()
                ->whereNull('parent_id')
                ->with([
                    'translations' => fn ($q) => $q->where('locale', $locale),
                    'children' => fn ($q) => $q->published()->orderBy('sort_order'),
                    'children.translations' => fn ($q) => $q->where('locale', $locale),
                ])
                ->orderBy('sort_order')
                ->get();
        });
    }

    /** @return LengthAwarePaginator<Product> */
    public function publishedList(string $locale, int $perPage = 24): LengthAwarePaginator
    {
        return Product::query()
            ->published()
            ->whereNull('parent_id')
            ->with(['translations' => fn ($q) => $q->where('locale', $locale)])
            ->orderBy('sort_order')
            ->paginate($perPage);
    }

    public function findPublishedBySlug(string $slug, string $locale): ?Product
    {
        return Cache::remember(
            "product.{$locale}.{$slug}",
            3600,
            fn () => Product::query()
                ->published()
                ->whereHas('translations', fn ($q) => $q->where('locale', $locale)->where('slug', $slug))
                ->with([
                    'translations' => fn ($q) => $q->where('locale', $locale),
                    'seoMeta' => fn ($q) => $q->where('locale', $locale),
                    'parent.translations' => fn ($q) => $q->where('locale', $locale),
                    'children' => fn ($q) => $q->published()->orderBy('sort_order'),
                    'children.translations' => fn ($q) => $q->where('locale', $locale),
                ])
                ->first()
        );
    }

    public function clearCache(): void
    {
        app(ContentCacheService::class)->forgetProducts();
    }
}
