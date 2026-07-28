<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

/**
 * Targeted cache invalidation for public content (avoids Cache::flush in production).
 */
class ContentCacheService
{
    private const TAG = 'public_content';

    public function __construct(
        protected LocaleService $locales,
    ) {}

    /**
     * @template T
     * @param  callable(): T  $callback
     * @return T
     */
    public function remember(string $key, int $ttlSeconds, callable $callback): mixed
    {
        if ($this->supportsTagging()) {
            return Cache::tags([self::TAG])->remember($key, $ttlSeconds, $callback);
        }

        return Cache::remember($key, $ttlSeconds, $callback);
    }

    public function forgetHome(): void
    {
        foreach ($this->localeCodes() as $locale) {
            Cache::forget("home.sections.{$locale}");
        }
    }

    public function forgetServices(): void
    {
        foreach ($this->localeCodes() as $locale) {
            Cache::forget("services.published.{$locale}");
        }
    }

    public function forgetProjects(): void
    {
        foreach ($this->localeCodes() as $locale) {
            Cache::forget("projects.featured.{$locale}");
        }
    }

    public function forgetIndustries(): void
    {
        foreach ($this->localeCodes() as $locale) {
            Cache::forget("industries.featured.{$locale}");
            Cache::forget("industries.list.{$locale}");
        }
    }

    public function forgetProducts(): void
    {
        foreach ($this->localeCodes() as $locale) {
            Cache::forget("products.root.{$locale}");
        }

        // Detail pages are cached per locale + slug (includes children + featured images).
        $slugs = \App\Models\ProductTranslation::query()
            ->distinct()
            ->pluck('slug')
            ->filter()
            ->all();

        foreach ($this->localeCodes() as $locale) {
            foreach ($slugs as $slug) {
                Cache::forget("product.{$locale}.{$slug}");
            }
        }
    }

    public function forgetProduct(string $locale, string $slug): void
    {
        Cache::forget("product.{$locale}.{$slug}");
    }

    public function forgetCertifications(): void
    {
        foreach ($this->localeCodes() as $locale) {
            Cache::forget("certifications.featured.{$locale}");
            Cache::forget("certifications.list.{$locale}");
        }
    }

    public function forgetPage(string $locale, string $slug): void
    {
        Cache::forget("page.{$locale}.{$slug}");
    }

    public function forgetService(string $locale, string $slug): void
    {
        Cache::forget("service.{$locale}.{$slug}");
    }

    public function forgetProject(string $locale, string $slug): void
    {
        Cache::forget("project.{$locale}.{$slug}");
    }

    public function forgetAllPublicCatalog(): void
    {
        $this->forgetHome();
        $this->forgetServices();
        $this->forgetProjects();
        $this->forgetIndustries();
        $this->forgetProducts();
        $this->forgetCertifications();
    }

    /** @return list<string> */
    protected function localeCodes(): array
    {
        return $this->locales->active()->pluck('code')->all() ?: ['ar', 'en'];
    }

    protected function supportsTagging(): bool
    {
        return in_array(config('cache.default'), ['redis', 'memcached', 'dynamodb'], true);
    }
}
