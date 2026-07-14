<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductTranslation;
use App\Models\SeoMeta;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Seeds the IKS product catalog (categories + products).
 *
 * This project uses parent products as categories (`products.parent_id`).
 * There is no `product_categories` pivot, and no `sku` / `status` columns —
 * published state uses `is_published`, copy lives in `product_translations`.
 *
 * Run: php artisan db:seed --class=ProductSeeder
 */
class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $this->resetCatalog();

        $biDirectional = $this->upsertCategory(
            nameEn: 'Bi-directional Scrapers',
            nameAr: 'كاشطات ثنائية الاتجاه',
            slug: 'bi-directional-scrapers',
            sortOrder: 1,
            icon: 'bi-arrow-left-right',
            summaryEn: 'Bi-directional pipeline scrapers for cleaning, wiping, brushing, and gauging operations.',
            summaryAr: 'كاشطات خطوط أنابيب ثنائية الاتجاه لأعمال التنظيف والمسح والفرشاة والقياس.',
        );

        $foam = $this->upsertCategory(
            nameEn: 'Foam Scrapers',
            nameAr: 'كاشطات الرغوة',
            slug: 'foam-scrapers',
            sortOrder: 2,
            icon: 'bi-droplet-half',
            summaryEn: 'Polyurethane foam scrapers in multiple densities and surface configurations.',
            summaryAr: 'كاشطات رغوة بولي يوريثان بكثافات وتكوينات سطح متعددة.',
        );

        $biDirectionalProducts = [
            [
                'en' => 'Bi-directional Disc Scraper',
                'ar' => 'كاشطة قرص ثنائية الاتجاه',
                'pdf' => 'IK-Saudi_ Bi_directional_disc_Scrapers.pdf',
            ],
            [
                'en' => 'Bi-directional Brush Scraper',
                'ar' => 'كاشطة فرشاة ثنائية الاتجاه',
                'pdf' => 'IK-Saudi_ Bi_directional_Brush_Scrapers.pdf',
            ],
            [
                'en' => 'Bi-directional Gauging Scraper',
                'ar' => 'كاشطة قياس ثنائية الاتجاه',
                'pdf' => 'IK-Saudi_ Bi_directional_gauging_Scrapers.pdf',
            ],
        ];

        $foamProducts = [
            [
                'en' => 'Foam Scraper',
                'ar' => 'كاشطة رغوة',
                'pdf' => 'IK-Saudi_ Foam_Scrapers_DATASHEET.pdf',
            ],
            [
                'en' => 'LD Bare Foam Scraper',
                'ar' => 'كاشطة رغوة منخفضة الكثافة (Bare)',
                'pdf' => 'IK-Saudi_ Foam_Scrapers_DATASHEET.pdf',
            ],
            [
                'en' => 'MD Bare Foam Scraper',
                'ar' => 'كاشطة رغوة متوسطة الكثافة (Bare)',
                'pdf' => 'IK-Saudi_ Foam_Scrapers_DATASHEET.pdf',
            ],
            [
                'en' => 'HD Bare Foam Scraper',
                'ar' => 'كاشطة رغوة عالية الكثافة (Bare)',
                'pdf' => 'IK-Saudi_ Foam_Scrapers_DATASHEET.pdf',
            ],
            [
                'en' => 'MD Criss-cross Foam Scraper',
                'ar' => 'كاشطة رغوة متوسطة الكثافة بنمط شبكي',
                'pdf' => 'IK-Saudi_ Foam_Scrapers_DATASHEET.pdf',
            ],
            [
                'en' => 'HD Criss-cross Foam Scraper',
                'ar' => 'كاشطة رغوة عالية الكثافة بنمط شبكي',
                'pdf' => 'IK-Saudi_ Foam_Scrapers_DATASHEET.pdf',
            ],
            [
                'en' => 'MD Silicon Carbide Foam Scraper',
                'ar' => 'كاشطة رغوة متوسطة الكثافة بكربيد السيليكون',
                'pdf' => 'IK-Saudi_ Foam_Scrapers_DATASHEET.pdf',
            ],
            [
                'en' => 'HD Silicon Carbide Foam Scraper',
                'ar' => 'كاشطة رغوة عالية الكثافة بكربيد السيليكون',
                'pdf' => 'IK-Saudi_ Foam_Scrapers_DATASHEET.pdf',
            ],
            [
                'en' => 'MD Wire Brush Foam Scraper',
                'ar' => 'كاشطة رغوة متوسطة الكثافة بفرشاة سلكية',
                'pdf' => 'IK-Saudi_ Foam_Scrapers_DATASHEET.pdf',
            ],
            [
                'en' => 'HD Wire Brush Foam Scraper',
                'ar' => 'كاشطة رغوة عالية الكثافة بفرشاة سلكية',
                'pdf' => 'IK-Saudi_ Foam_Scrapers_DATASHEET.pdf',
            ],
        ];

        $sort = 1;
        foreach ($biDirectionalProducts as $item) {
            $this->upsertProduct($item, $biDirectional, $sort++);
        }

        $sort = 1;
        foreach ($foamProducts as $item) {
            $this->upsertProduct($item, $foam, $sort++);
        }

        $this->command?->info(sprintf(
            'ProductSeeder finished: %d categories, %d products.',
            2,
            count($biDirectionalProducts) + count($foamProducts),
        ));
    }

    private function resetCatalog(): void
    {
        SeoMeta::query()
            ->where('seoable_type', Product::class)
            ->delete();

        Schema::disableForeignKeyConstraints();

        try {
            if (Schema::hasTable('product_translations')) {
                DB::table('product_translations')->truncate();
            }

            // Soft-deleted rows are not cleared by truncate alone on some setups —
            // wipe fully, then truncate for a clean auto-increment.
            Product::withTrashed()->forceDelete();
            DB::table('products')->truncate();
        } finally {
            Schema::enableForeignKeyConstraints();
        }
    }

    private function upsertCategory(
        string $nameEn,
        string $nameAr,
        string $slug,
        int $sortOrder,
        string $icon,
        string $summaryEn,
        string $summaryAr,
    ): Product {
        $product = Product::query()->updateOrCreate(
            ['uuid' => $this->deterministicUuid('category-'.$slug)],
            [
                'parent_id' => null,
                'icon' => $icon,
                'is_featured' => true,
                'is_published' => true,
                'published_at' => now(),
                'sort_order' => $sortOrder,
                'pdf_path' => null,
                'featured_image' => null,
            ],
        );

        $this->syncTranslations($product, $slug, $nameEn, $nameAr, $summaryEn, $summaryAr);

        return $product;
    }

    /**
     * @param  array{en: string, ar: string, pdf?: string|null}  $item
     */
    private function upsertProduct(array $item, Product $category, int $sortOrder): Product
    {
        $nameEn = $item['en'];
        $nameAr = $item['ar'];
        $slug = Str::slug($nameEn);

        $pdfPath = $this->attachPdfIfPresent($slug, $item['pdf'] ?? null);

        $product = Product::query()->updateOrCreate(
            ['uuid' => $this->deterministicUuid('product-'.$slug)],
            [
                'parent_id' => $category->id,
                'icon' => $category->icon,
                'is_featured' => $sortOrder <= 3,
                'is_published' => true,
                'published_at' => now(),
                'sort_order' => $sortOrder,
                'pdf_path' => $pdfPath,
                'featured_image' => null,
            ],
        );

        $this->syncTranslations(
            $product,
            $slug,
            $nameEn,
            $nameAr,
            $nameEn,
            $nameAr,
        );

        $this->command?->info("Seeded: {$nameEn} → {$category->translate('en')?->title}");

        return $product;
    }

    private function syncTranslations(
        Product $product,
        string $slug,
        string $titleEn,
        string $titleAr,
        string $summaryEn,
        string $summaryAr,
    ): void {
        ProductTranslation::query()->updateOrCreate(
            ['product_id' => $product->id, 'locale' => 'en'],
            [
                'title' => $titleEn,
                'slug' => $slug,
                'summary' => $summaryEn,
                'body' => '<p>'.e($summaryEn).'</p>',
            ],
        );

        ProductTranslation::query()->updateOrCreate(
            ['product_id' => $product->id, 'locale' => 'ar'],
            [
                'title' => $titleAr,
                'slug' => $slug,
                'summary' => $summaryAr,
                'body' => '<p>'.e($summaryAr).'</p>',
            ],
        );
    }

    private function attachPdfIfPresent(string $slug, ?string $pdfFilename): ?string
    {
        if (! $pdfFilename) {
            return null;
        }

        $source = resource_path('ik_products'.DIRECTORY_SEPARATOR.$pdfFilename);
        if (! is_file($source)) {
            $this->command?->warn("PDF not found for [{$slug}]: {$pdfFilename}");

            return null;
        }

        $target = "products/{$slug}/datasheet.pdf";

        try {
            Storage::disk('public')->put($target, (string) file_get_contents($source));

            return $target;
        } catch (\Throwable $e) {
            $this->command?->warn("Could not store PDF for [{$slug}]: {$e->getMessage()}");

            return null;
        }
    }

    private function deterministicUuid(string $key): string
    {
        $hash = md5('ik-saudi.products.'.$key);

        return sprintf(
            '%s-%s-%s-%s-%s',
            substr($hash, 0, 8),
            substr($hash, 8, 4),
            substr($hash, 12, 4),
            substr($hash, 16, 4),
            substr($hash, 20, 12),
        );
    }
}
