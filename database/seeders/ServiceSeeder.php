<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\ServiceTranslation;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'sort_order' => 1,
                'is_featured' => true,
                'translations' => [
                    'ar' => [
                        'title' => 'كشط خطوط الأنابيب',
                        'slug' => 'pipeline-scraping',
                        'summary' => 'منتجات وخدمات كشط خطوط الأنابيب لقطاع النفط والغاز.',
                        'body' => '<p>نصمم ونصنع أدوات كشط خطوط الأنابيب وفق أعلى معايير الجودة والسلامة لعمليات خطوط الأنابيب.</p>',
                    ],
                    'en' => [
                        'title' => 'Pipeline Scraping',
                        'slug' => 'pipeline-scraping',
                        'summary' => 'Pipeline scraping products and services for the Oil & Gas sector.',
                        'body' => '<p>We design and manufacture pipeline scraping tools to the highest quality and safety standards for pipeline operations.</p>',
                    ],
                ],
            ],
            [
                'sort_order' => 2,
                'is_featured' => true,
                'translations' => [
                    'ar' => [
                        'title' => 'حلول البولي يوريثان تحت البحرية',
                        'slug' => 'polyurethane-subsea',
                        'summary' => 'منتجات بولي يوريثان للتطبيقات تحت البحرية وغير المعدنية.',
                        'body' => '<p>حلول متقدمة من البولي يوريثان للبنية التحتية تحت البحرية وقطاع التعدين.</p>',
                    ],
                    'en' => [
                        'title' => 'Polyurethane Subsea Solutions',
                        'slug' => 'polyurethane-subsea',
                        'summary' => 'Polyurethane products for subsea and non-metallic applications.',
                        'body' => '<p>Advanced polyurethane solutions for subsea infrastructure and the mining sector.</p>',
                    ],
                ],
            ],
            [
                'sort_order' => 3,
                'is_featured' => false,
                'translations' => [
                    'ar' => [
                        'title' => 'تدخل خطوط الأنابيب',
                        'slug' => 'pipeline-intervention',
                        'summary' => 'تقنيات وخدمات تدخل خطوط الأنابيب للعمليات الحرجة.',
                        'body' => '<p>دعم تشغيلي وتقني شامل لعمليات تدخل خطوط الأنابيب في المنشآت الصناعية.</p>',
                    ],
                    'en' => [
                        'title' => 'Pipeline Intervention',
                        'slug' => 'pipeline-intervention',
                        'summary' => 'Intervention technologies and services for critical pipeline operations.',
                        'body' => '<p>Comprehensive technical and operational support for pipeline intervention in industrial facilities.</p>',
                    ],
                ],
            ],
        ];

        foreach ($services as $data) {
            $translations = $data['translations'];
            unset($data['translations']);

            $slug = $translations['en']['slug'];

            $service = Service::query()->firstOrCreate(
                ['uuid' => 'seed-'.$slug],
                array_merge($data, [
                    'is_published' => true,
                    'published_at' => now(),
                ]),
            );

            foreach ($translations as $locale => $fields) {
                ServiceTranslation::query()->updateOrCreate(
                    ['service_id' => $service->id, 'locale' => $locale],
                    $fields,
                );
            }
        }
    }
}
