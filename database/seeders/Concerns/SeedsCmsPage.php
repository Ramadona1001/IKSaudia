<?php

namespace Database\Seeders\Concerns;

use App\Models\Page;
use App\Models\PageTranslation;
use App\Models\SeoMeta;

trait SeedsCmsPage
{
    /**
     * @param  array{
     *     key: string,
     *     slug: string,
     *     sort_order: int,
     *     template?: string,
     *     translations: array<string, array{title: string, excerpt: string, body: string}>,
     *     seo: array<string, array{meta_title: string, meta_description: string}>
     * }  $definition
     */
    protected function seedCmsPage(array $definition): Page
    {
        $page = Page::query()->updateOrCreate(
            ['key' => $definition['key']],
            [
                'template' => $definition['template'] ?? 'default',
                'is_published' => true,
                'published_at' => now(),
                'sort_order' => $definition['sort_order'],
            ],
        );

        foreach ($definition['translations'] as $locale => $content) {
            PageTranslation::query()->updateOrCreate(
                ['page_id' => $page->id, 'locale' => $locale],
                [
                    'title' => $content['title'],
                    'slug' => $definition['slug'],
                    'excerpt' => $content['excerpt'],
                    'body' => $content['body'],
                ],
            );
        }

        foreach ($definition['seo'] as $locale => $meta) {
            SeoMeta::query()->updateOrCreate(
                [
                    'seoable_type' => Page::class,
                    'seoable_id' => $page->id,
                    'locale' => $locale,
                ],
                $meta,
            );
        }

        return $page;
    }
}
