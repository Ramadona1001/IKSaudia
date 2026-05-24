<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductTranslation;
use DOMDocument;
use DOMXPath;
use App\Services\Http\SafeHttpClient;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

final class ProductLegacyImporter
{
    private const BASE_URL = 'https://www.iksaudi.com/';

    /** @var array<int, Product> */
    private array $importedByLegacyId = [];

    /** @var list<array{legacy_id: int, children: list<int>}> */
    private const MENU_TREE = [
        ['legacy_id' => 259, 'children' => [265, 266, 267, 268, 269, 270, 271, 272, 273, 274, 275, 276, 277, 278, 279, 280, 281]],
        ['legacy_id' => 260, 'children' => [282, 283, 284, 285, 286]],
        ['legacy_id' => 261, 'children' => []],
        ['legacy_id' => 262, 'children' => [287, 288, 289, 290, 291]],
        ['legacy_id' => 263, 'children' => [292, 293]],
        ['legacy_id' => 264, 'children' => [294, 295, 296]],
    ];

    public function import(): int
    {
        $count = 0;
        $sort = 0;

        foreach (self::MENU_TREE as $node) {
            $parent = $this->importLegacyPage($node['legacy_id'], null, $sort++);
            $count++;

            $childSort = 0;
            foreach ($node['children'] as $childId) {
                $this->importLegacyPage($childId, $parent, $childSort++);
                $count++;
            }
        }

        return $count;
    }

    private function importLegacyPage(int $legacyId, ?Product $parent, int $sortOrder): Product
    {
        if (isset($this->importedByLegacyId[$legacyId])) {
            return $this->importedByLegacyId[$legacyId];
        }

        $path = $this->discoverPathForId($legacyId);
        $html = $this->fetchHtml($path);
        $parsed = $this->parsePage($html, $legacyId, $path);

        $product = Product::query()->updateOrCreate(
            ['legacy_page_id' => $legacyId],
            [
                'parent_id' => $parent?->getKey(),
                'legacy_path' => $path,
                'featured_image' => $this->downloadAsset($parsed['image_url'], "products/{$legacyId}/featured".($parsed['image_ext'] ?? '.jpg')),
                'pdf_path' => $parsed['pdf_url'] ? $this->downloadAsset($parsed['pdf_url'], "products/{$legacyId}/datasheet.pdf", false) : null,
                'is_featured' => $parent === null,
                'is_published' => true,
                'published_at' => now(),
                'sort_order' => $sortOrder,
            ],
        );

        foreach (['en', 'ar'] as $locale) {
            ProductTranslation::query()->updateOrCreate(
                ['product_id' => $product->id, 'locale' => $locale],
                [
                    'title' => $parsed['title'],
                    'slug' => $this->uniqueSlug($parsed['slug'], $locale, $product->id),
                    'summary' => $parsed['summary'],
                    'body' => $parsed['body'],
                ],
            );
        }

        return $this->importedByLegacyId[$legacyId] = $product->fresh(['translations']);
    }

    private function discoverPathForId(int $legacyId): string
    {
        $menuHtml = $this->fetchHtml('Our_Products-258.html');

        if (preg_match('/href="https?:\/\/[^"]+\/([^"]+-'.$legacyId.')\.html"/i', $menuHtml, $m)) {
            return $m[1].'.html';
        }

        throw new \RuntimeException("Could not discover legacy path for page id {$legacyId}");
    }

    private function fetchHtml(string $path): string
    {
        $response = app(SafeHttpClient::class)
            ->get(self::BASE_URL.$path);

        $response->throw();

        return $response->body();
    }

    /**
     * @return array{title: string, slug: string, summary: ?string, body: ?string, image_url: ?string, image_ext: ?string, pdf_url: ?string}
     */
    private function parsePage(string $html, int $legacyId, string $path): array
    {
        $dom = new DOMDocument;
        @$dom->loadHTML('<?xml encoding="UTF-8">'.$html);
        $xpath = new DOMXPath($dom);

        $title = $this->textOf($xpath, '//div[contains(@class,"title-box")]//h1')
            ?? $this->textOf($xpath, '//div[contains(@class,"ser_content")]//h3')
            ?? $this->titleFromPath($path);

        $summary = $this->metaContent($html, 'description');
        $body = $this->extractSerContent($html);
        $imageUrl = $this->firstMatch($html, '/<div class="ser_thumb">\s*<img[^>]+src="([^"]+)"/i')
            ?? $this->firstMatch($html, '/<div class="singleblog">.*?<img[^>]+src="([^"]+)"/is');

        if ($imageUrl) {
            $imageUrl = $this->absoluteUrl($imageUrl);
        }

        $pdfUrl = $this->firstMatch($html, '/href="([^"]+\.pdf)"/i');
        if ($pdfUrl) {
            $pdfUrl = $this->absoluteUrl($pdfUrl);
        }

        if (! $body && preg_match_all('/<div class="singleblog">(.*?)<\/div>\s*<\/div>/is', $html, $cards)) {
            $body = null;
            if ($imageUrl === null && preg_match('/<img[^>]+src="([^"]+)"/i', $cards[0][0] ?? '', $img)) {
                $imageUrl = $this->absoluteUrl($img[1]);
            }
        }

        $slug = Str::slug($title);
        if ($slug === '') {
            $slug = 'product-'.$legacyId;
        }

        return [
            'title' => $title,
            'slug' => $slug,
            'summary' => $summary ? Str::limit(strip_tags($summary), 500) : null,
            'body' => $body,
            'image_url' => $imageUrl,
            'image_ext' => $imageUrl ? '.'.pathinfo(parse_url($imageUrl, PHP_URL_PATH), PATHINFO_EXTENSION) : null,
            'pdf_url' => $pdfUrl,
        ];
    }

    private function downloadAsset(?string $url, string $storagePath, bool $required = true): ?string
    {
        if (! $url) {
            return null;
        }

        try {
            $client = app(SafeHttpClient::class);
            $client->assertAllowedUrl($url);
            $response = $client->get($url);

            if (! $response->successful()) {
                return $required ? null : null;
            }

            Storage::disk('public')->put($storagePath, $response->body());

            return $storagePath;
        } catch (\Throwable) {
            return null;
        }
    }

    private function uniqueSlug(string $slug, string $locale, int $productId): string
    {
        $base = $slug;
        $candidate = $base;
        $i = 0;

        while (
            ProductTranslation::query()
                ->where('locale', $locale)
                ->where('slug', $candidate)
                ->where('product_id', '!=', $productId)
                ->exists()
        ) {
            $candidate = $base.'-'.(++$i);
        }

        return $candidate;
    }

    private function absoluteUrl(string $url): string
    {
        if (str_starts_with($url, 'http')) {
            return $url;
        }

        return rtrim(self::BASE_URL, '/').'/'.ltrim($url, '/');
    }

    private function titleFromPath(string $path): string
    {
        $name = preg_replace('/-\d+\.html$/', '', $path) ?? $path;

        return Str::title(str_replace('_', ' ', $name));
    }

    private function metaContent(string $html, string $name): ?string
    {
        if (preg_match('/<meta[^>]+http-equiv="'.$name.'"[^>]+content="([^"]*)"/i', $html, $m)) {
            return html_entity_decode($m[1], ENT_QUOTES | ENT_HTML5);
        }

        return null;
    }

    private function firstMatch(string $html, string $pattern): ?string
    {
        return preg_match($pattern, $html, $m) ? $m[1] : null;
    }

    private function textOf(DOMXPath $xpath, string $query): ?string
    {
        $node = $xpath->query($query)?->item(0);

        return $node?->textContent ? trim($node->textContent) : null;
    }

    private function extractSerContent(string $html): ?string
    {
        if (! preg_match('/<div class="ser_content">(.*?)<\/div>\s*(?:<div class="clearfix"|<a href="[^"]+\.pdf)/is', $html, $m)) {
            return null;
        }

        $content = $m[1];
        $content = preg_replace('/<h3>.*?<\/h3>/is', '', $content, 1) ?? $content;
        $content = trim($content);

        return $content !== '' ? $content : null;
    }
}
