<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductTranslation;
use Illuminate\Database\Seeder;

/**
 * Seeds the core IKS product catalog (pipeline scraping & pigging solutions).
 *
 * Run: php artisan db:seed --class=ProductSeeder
 */
class ProductSeeder extends Seeder
{
    public function run(): void
    {
        foreach (self::definition() as $data) {
            $translations = $data['translations'];
            unset($data['translations']);

            $product = Product::query()->updateOrCreate(
                ['uuid' => $data['uuid']],
                array_merge($data, [
                    'is_published' => true,
                    'published_at' => now(),
                ]),
            );

            foreach ($translations as $locale => $fields) {
                ProductTranslation::query()->updateOrCreate(
                    ['product_id' => $product->id, 'locale' => $locale],
                    $fields,
                );
            }
        }

        $slugs = collect(self::definition())
            ->map(fn (array $row): string => $row['translations']['en']['slug'])
            ->all();

        Product::query()
            ->whereDoesntHave('translations', fn ($query) => $query
                ->where('locale', 'en')
                ->whereIn('slug', $slugs))
            ->update(['is_published' => false]);
    }

    /**
     * @return list<array<string, mixed>>
     */
    public static function definition(): array
    {
        return [
            [
                'uuid' => 'seed-foam-scraper',
                'sort_order' => 1,
                'is_featured' => true,
                'icon' => 'bi-droplet-half',
                'translations' => [
                    'ar' => [
                        'title' => 'Foam Scraper',
                        'slug' => 'foam-scraper',
                        'summary' => 'تنظيف دوري سريع يحافظ على تدفق الأنابيب ويطيل عمرها في مختلف البيئات التشغيلية.',
                        'body' => '<p>تنظيف دوري سريع يحافظ على تدفق الأنابيب ويطيل عمرها في مختلف البيئات التشغيلية.</p>',
                    ],
                    'en' => [
                        'title' => 'Foam Scraper',
                        'slug' => 'foam-scraper',
                        'summary' => 'Fast periodic cleaning that maintains pipeline flow and extends service life across diverse operating environments.',
                        'body' => '<p>Fast periodic cleaning that maintains pipeline flow and extends service life across diverse operating environments.</p>',
                    ],
                ],
            ],
            [
                'uuid' => 'seed-mechanical-scraper',
                'sort_order' => 2,
                'is_featured' => true,
                'icon' => 'bi-gear-wide-connected',
                'translations' => [
                    'ar' => [
                        'title' => 'Mechanical Scraper',
                        'slug' => 'mechanical-scraper',
                        'summary' => 'إزالة الرواسب الصعبة في خطوط الأنابيب المعقدة وبيئات الضغط العالي، مع تكوينات مخصصة لكل مشروع.',
                        'body' => '<p>إزالة الرواسب الصعبة في خطوط الأنابيب المعقدة وبيئات الضغط العالي، مع تكوينات مخصصة لكل مشروع.</p>',
                    ],
                    'en' => [
                        'title' => 'Mechanical Scraper',
                        'slug' => 'mechanical-scraper',
                        'summary' => 'Removal of tough deposits in complex pipeline routes and high-pressure environments, with custom configurations for every project.',
                        'body' => '<p>Removal of tough deposits in complex pipeline routes and high-pressure environments, with custom configurations for every project.</p>',
                    ],
                ],
            ],
            [
                'uuid' => 'seed-non-metallic-flange-shroud',
                'sort_order' => 3,
                'is_featured' => true,
                'icon' => 'bi-shield-check',
                'translations' => [
                    'ar' => [
                        'title' => 'Non-Metallic Flange Shroud (حصري)',
                        'slug' => 'non-metallic-flange-shroud',
                        'summary' => 'حماية وقائية للوصلات ضد التآكل والتلوث، مع تقليل تكاليف الاستبدال على المدى البعيد.',
                        'body' => '<p>حماية وقائية للوصلات ضد التآكل والتلوث، مع تقليل تكاليف الاستبدال على المدى البعيد.</p>',
                    ],
                    'en' => [
                        'title' => 'Non-Metallic Flange Shroud (Exclusive)',
                        'slug' => 'non-metallic-flange-shroud',
                        'summary' => 'Protective shielding for flange connections against corrosion and contamination, reducing long-term replacement costs.',
                        'body' => '<p>Protective shielding for flange connections against corrosion and contamination, reducing long-term replacement costs.</p>',
                    ],
                ],
            ],
            [
                'uuid' => 'seed-pipeline-pigging-solutions',
                'sort_order' => 4,
                'is_featured' => true,
                'icon' => 'bi-signpost-split',
                'translations' => [
                    'ar' => [
                        'title' => 'Pipeline Pigging Solutions',
                        'slug' => 'pipeline-pigging-solutions',
                        'summary' => 'حلول متكاملة لفحص وتنظيف وصيانة خطوط الأنابيب، مدعومة بخبرة هندسية مثبتة منذ 2013.',
                        'body' => '<p>حلول متكاملة لفحص وتنظيف وصيانة خطوط الأنابيب، مدعومة بخبرة هندسية مثبتة منذ 2013.</p>',
                    ],
                    'en' => [
                        'title' => 'Pipeline Pigging Solutions',
                        'slug' => 'pipeline-pigging-solutions',
                        'summary' => 'Integrated solutions for pipeline inspection, cleaning, and maintenance, backed by proven engineering expertise since 2013.',
                        'body' => '<p>Integrated solutions for pipeline inspection, cleaning, and maintenance, backed by proven engineering expertise since 2013.</p>',
                    ],
                ],
            ],
        ];
    }
}
