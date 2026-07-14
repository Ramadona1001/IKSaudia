<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductTranslation;
use App\Models\SeoMeta;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

/**
 * Seeds the official IKS product catalog from website content.
 *
 * Categories are parent products (`parent_id`); children are the listed products.
 *
 * Run: php artisan db:seed --class=ProductSeeder
 */
class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $this->resetCatalog();

        $pipeline = $this->upsertCategory(
            nameEn: 'Pipeline Scrapers',
            nameAr: 'كاشطات خطوط الأنابيب',
            slug: 'pipeline-scrapers',
            sortOrder: 1,
            icon: 'bi-pipe',
            summaryEn: 'Pipeline scraping and pigging solutions for cleaning, inspection, and maintenance.',
            summaryAr: 'حلول كشط وتنظيف خطوط الأنابيب للفحص والصيانة والحفاظ على تدفق التشغيل.',
        );

        $polyurethane = $this->upsertCategory(
            nameEn: 'Non-Metallic Polyurethane Products',
            nameAr: 'منتجات البولي يوريثان غير المعدنية',
            slug: 'non-metallic-polyurethane-products',
            sortOrder: 2,
            icon: 'bi-box-seam',
            summaryEn: 'Thermoset polyurethane solutions for subsea, mining, marine, and custom industrial applications.',
            summaryAr: 'حلول بولي يوريثان حرارية لتطبيقات تحت البحر والتعدين والبحرية والصناعات المخصصة.',
        );

        $pipelineProducts = [
            [
                'en' => 'Mechanical Scrapers',
                'ar' => 'كاشطات ميكانيكية',
                'summary_en' => 'Robust pipeline maintenance tools designed to clean, separate, and inspect pipelines by removing debris, wax, scale, and other deposits while maintaining optimal flow efficiency.',
                'summary_ar' => 'أدوات صيانة قوية لخطوط الأنابيب مصممة للتنظيف والفصل والفحص عبر إزالة الرواسب والشمع والقشور والشوائب مع الحفاظ على كفاءة التدفق.',
                'icon' => 'bi-gear-wide-connected',
            ],
            [
                'en' => 'Foam Scrapers',
                'ar' => 'كاشطات الرغوة',
                'summary_en' => 'Flexible and lightweight pipeline cleaning solutions used for dewatering, drying, product separation, and the removal of loose debris in pipelines of varying diameters and configurations.',
                'summary_ar' => 'حلول تنظيف مرنة وخفيفة لخطوط الأنابيب تُستخدم لنزع المياه والتجفيف وفصل المنتجات وإزالة الرواسب الرخوة في أقطار وتكوينات متنوعة.',
                'icon' => 'bi-droplet-half',
            ],
            [
                'en' => 'Mechanical Scraper Spare Parts',
                'ar' => 'قطع غيار الكاشطات الميكانيكية',
                'summary_en' => 'High-quality replacement components such as cups, discs, brushes, magnets, and hardware that ensure reliable performance, extended service life, and efficient maintenance of scraper systems.',
                'summary_ar' => 'مكونات بديلة عالية الجودة مثل الأكواب والأقراص والفرش والمغناطيسات والملحقات لضمان أداء موثوق وعمر افتراضي أطول وصيانة فعّالة لأنظمة الكاشطات.',
                'icon' => 'bi-tools',
            ],
        ];

        $polyurethaneProducts = [
            [
                'en' => 'Non-Metallic Subsea Flange Shroud',
                'ar' => 'غلاف شفة تحت البحر غير المعدني',
                'summary_en' => 'Featuring an anti-snag design that prevents anchor wires from catching on protruding stud lengths of subsea flanges. The shroud sits on the seabed, covering the entire flange assembly. Manufactured from thermoset polyurethane polymers specifically designed for submerged seawater environments, with a design life of up to 25 years.',
                'summary_ar' => 'بتصميم مضاد للاشتباك يمنع تعلق أسلاك المرساة بأطوال المسامير البارزة لشفاه تحت البحر. يستقر الغلاف على قاع البحر ويغطي تجميعة الشفة بالكامل. مصنوع من بوليمرات بولي يوريثان حرارية مخصصة لبيئات مياه البحر، بعمر تصميمي يصل إلى 25 عامًا.',
                'icon' => 'bi-shield-check',
            ],
            [
                'en' => 'Polyurethane Mining Screens',
                'ar' => 'شاشات تعدين من البولي يوريثان',
                'summary_en' => 'High-performance screening solutions engineered for superior wear resistance, efficient material separation, and extended service life in demanding mining and mineral processing applications.',
                'summary_ar' => 'حلول غربلة عالية الأداء مصممة لمقاومة تآكل فائقة وفصل فعّال للمواد وعمر تشغيلي أطول في تطبيقات التعدين ومعالجة المعادن القاسية.',
                'icon' => 'bi-grid-3x3-gap',
            ],
            [
                'en' => 'Marine Foam Fenders',
                'ar' => 'مصدات رغوة بحرية',
                'summary_en' => 'Durable, energy-absorbing protection systems designed to safeguard vessels, docks, and marine structures from impact during berthing and mooring operations.',
                'summary_ar' => 'أنظمة حماية متينة ماصة للطاقة مصممة لحماية السفن والأرصفة والمنشآت البحرية من الصدمات أثناء الرسو والربط.',
                'icon' => 'bi-water',
            ],
            [
                'en' => 'Non-Metallic Customized Products',
                'ar' => 'منتجات غير معدنية مخصصة',
                'summary_en' => 'Tailor-made polyurethane and other non-metallic solutions designed to meet specific customer requirements, operating conditions, and performance objectives. Engineered for durability, corrosion resistance, and long service life across industrial, marine, mining, and subsea applications.',
                'summary_ar' => 'حلول بولي يوريثان وغير معدنية مخصصة لتلبية متطلبات العملاء وظروف التشغيل وأهداف الأداء. مصممة للمتانة ومقاومة التآكل والعمر الطويل في التطبيقات الصناعية والبحرية والتعدين وتحت البحر.',
                'icon' => 'bi-puzzle',
            ],
        ];

        $sort = 1;
        foreach ($pipelineProducts as $item) {
            $this->upsertProduct($item, $pipeline, $sort++);
        }

        $sort = 1;
        foreach ($polyurethaneProducts as $item) {
            $this->upsertProduct($item, $polyurethane, $sort++);
        }

        $this->command?->info(sprintf(
            'ProductSeeder finished: 2 categories, %d products.',
            count($pipelineProducts) + count($polyurethaneProducts),
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
     * @param  array{en: string, ar: string, summary_en: string, summary_ar: string, icon?: string}  $item
     */
    private function upsertProduct(array $item, Product $category, int $sortOrder): Product
    {
        $nameEn = $item['en'];
        $nameAr = $item['ar'];
        $slug = Str::slug($nameEn);
        $summaryEn = $item['summary_en'];
        $summaryAr = $item['summary_ar'];
        $featuredImage = $this->storeSeedImage($slug);

        $product = Product::query()->updateOrCreate(
            ['uuid' => $this->deterministicUuid('product-'.$slug)],
            [
                'parent_id' => $category->id,
                'icon' => $item['icon'] ?? $category->icon,
                'is_featured' => true,
                'is_published' => true,
                'published_at' => now(),
                'sort_order' => $sortOrder,
                'pdf_path' => null,
                'featured_image' => $featuredImage,
            ],
        );

        $this->syncTranslations($product, $slug, $nameEn, $nameAr, $summaryEn, $summaryAr);

        $this->command?->info("Seeded: {$nameEn} → {$category->translate('en')?->title}");

        return $product;
    }

    private function storeSeedImage(string $slug): ?string
    {
        $source = database_path('seeders/data/products/'.$slug.'/featured.jpg');
        if (! is_file($source)) {
            return null;
        }

        $target = "products/{$slug}/featured.jpg";

        try {
            \Illuminate\Support\Facades\Storage::disk('public')->put(
                $target,
                (string) file_get_contents($source),
            );

            return $target;
        } catch (\Throwable $e) {
            $this->command?->warn("Could not store image for [{$slug}]: {$e->getMessage()}");

            return null;
        }
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
