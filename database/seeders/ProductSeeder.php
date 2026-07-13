<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductTranslation;
use App\Models\SeoMeta;
use App\Support\IkProductCatalogReader;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Throwable;

/**
 * Seeds products from resources/ik_products (PDFs + optional images).
 *
 * Run:
 *   php artisan db:seed --class=ProductSeeder
 *
 * Folder layout:
 *   resources/ik_products/*.pdf
 *   resources/ik_products/image-map.json          (optional mapping)
 *   resources/ik_products/images/{slug}/*         (optional per-product images)
 *   resources/ik_products/Products with Background|Products in Factory|People in Action/*
 */
class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $this->resetProducts();

        $reader = new IkProductCatalogReader(IkProductCatalogReader::defaultRoot());
        $products = $reader->products();

        if ($products->isEmpty()) {
            $this->command?->warn('No products found in resources/ik_products. Nothing seeded.');

            return;
        }

        $disk = Storage::disk('public');
        // Clear previous seeded product media so reruns stay idempotent on disk too.
        if ($disk->exists('products')) {
            $disk->deleteDirectory('products');
        }

        $created = 0;

        foreach ($products as $item) {
            try {
                $featuredPath = null;
                if (! empty($item['featured_source']) && is_file($item['featured_source'])) {
                    $featuredPath = $this->storeImage(
                        $item['featured_source'],
                        "products/{$item['slug']}/featured".$this->extensionFor($item['featured_source']),
                    );
                }

                // Extra images (project schema only has featured_image; store extras on disk for later use).
                $galleryIndex = 1;
                foreach ($item['gallery_sources'] as $source) {
                    if ($featuredPath && realpath($source) === realpath($item['featured_source'] ?? '')) {
                        continue;
                    }
                    if (! is_file($source)) {
                        continue;
                    }
                    $this->storeImage(
                        $source,
                        sprintf('products/%s/gallery/%02d%s', $item['slug'], $galleryIndex++, $this->extensionFor($source)),
                    );
                }

                $pdfPath = $this->storeFile(
                    $item['pdf_absolute'],
                    "products/{$item['slug']}/datasheet.pdf",
                );

                $product = Product::query()->create([
                    'uuid' => $item['uuid'],
                    'featured_image' => $featuredPath,
                    'pdf_path' => $pdfPath,
                    'icon' => $item['icon'],
                    'is_featured' => true,
                    'is_published' => true,
                    'published_at' => now(),
                    'sort_order' => $item['sort_order'],
                ]);

                ProductTranslation::query()->create([
                    'product_id' => $product->id,
                    'locale' => 'en',
                    'title' => $item['title_en'],
                    'slug' => $item['slug'],
                    'summary' => $item['summary_en'],
                    'body' => $item['body_en'],
                ]);

                ProductTranslation::query()->create([
                    'product_id' => $product->id,
                    'locale' => 'ar',
                    'title' => $item['title_ar'],
                    'slug' => $item['slug'],
                    'summary' => $item['summary_ar'],
                    'body' => $item['body_ar'],
                ]);

                $created++;
                $this->command?->info("Seeded product: {$item['title_en']} ({$item['slug']})");

                foreach ($item['warnings'] as $warning) {
                    $this->command?->warn('  · '.$warning);
                }
            } catch (Throwable $e) {
                Log::warning('ProductSeeder: failed to seed product.', [
                    'slug' => $item['slug'] ?? null,
                    'error' => $e->getMessage(),
                ]);
                $this->command?->warn("Failed seeding [{$item['slug']}]: {$e->getMessage()}");
            }
        }

        $this->cleanupTempExtracts();

        $this->command?->info("ProductSeeder finished. Created {$created} product(s).");
    }

    private function resetProducts(): void
    {
        SeoMeta::query()
            ->where('seoable_type', Product::class)
            ->delete();

        Schema::disableForeignKeyConstraints();

        try {
            // Force-delete soft-deleted rows first, then truncate for a clean slate.
            Product::withTrashed()->get()->each(function (Product $product): void {
                $product->translations()->delete();
                $product->forceDelete();
            });

            if (Schema::hasTable('product_translations')) {
                DB::table('product_translations')->truncate();
            }
            DB::table('products')->truncate();
        } finally {
            Schema::enableForeignKeyConstraints();
        }
    }

    private function storeImage(string $absoluteSource, string $storageRelative): ?string
    {
        try {
            $contents = file_get_contents($absoluteSource);
            if ($contents === false) {
                Log::warning('ProductSeeder: could not read image.', ['source' => $absoluteSource]);

                return null;
            }

            Storage::disk('public')->put($storageRelative, $contents);

            return $storageRelative;
        } catch (Throwable $e) {
            Log::warning('ProductSeeder: image store failed.', [
                'source' => $absoluteSource,
                'target' => $storageRelative,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    private function storeFile(string $absoluteSource, string $storageRelative): ?string
    {
        try {
            $contents = file_get_contents($absoluteSource);
            if ($contents === false) {
                Log::warning('ProductSeeder: could not read file.', ['source' => $absoluteSource]);

                return null;
            }

            Storage::disk('public')->put($storageRelative, $contents);

            return $storageRelative;
        } catch (Throwable $e) {
            Log::warning('ProductSeeder: file store failed.', [
                'source' => $absoluteSource,
                'target' => $storageRelative,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    private function extensionFor(string $path): string
    {
        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        return in_array($ext, ['jpg', 'jpeg', 'png', 'webp'], true)
            ? '.'.($ext === 'jpeg' ? 'jpg' : $ext)
            : '.jpg';
    }

    private function cleanupTempExtracts(): void
    {
        $tmp = storage_path('app/tmp/ik_products');
        if (! is_dir($tmp)) {
            return;
        }

        try {
            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($tmp, \FilesystemIterator::SKIP_DOTS),
                \RecursiveIteratorIterator::CHILD_FIRST
            );
            foreach ($iterator as $file) {
                $file->isDir() ? @rmdir($file->getPathname()) : @unlink($file->getPathname());
            }
            @rmdir($tmp);
        } catch (Throwable) {
            // Non-fatal
        }
    }
}
