<?php

namespace Database\Seeders;

use App\Models\Industry;
use App\Models\IndustryTranslation;
use Illuminate\Database\Seeder;

class IndustrySeeder extends Seeder
{
    public function run(): void
    {
        $industries = [
            [
                'icon' => 'oil-gas',
                'sort_order' => 1,
                'is_featured' => true,
                'translations' => [
                    'ar' => ['title' => 'النفط والغاز', 'slug' => 'oil-gas', 'summary' => 'حلول تدخل خطوط الأنابيب وصيانة البنية التحتية لقطاع الطاقة.'],
                    'en' => ['title' => 'Oil & Gas', 'slug' => 'oil-gas', 'summary' => 'Pipeline intervention and infrastructure maintenance for the energy sector.'],
                ],
            ],
            [
                'icon' => 'mining',
                'sort_order' => 2,
                'is_featured' => true,
                'translations' => [
                    'ar' => ['title' => 'التعدين', 'slug' => 'mining', 'summary' => 'منتجات بولي يوريثان وتطبيقات صناعية لعمليات التعدين.'],
                    'en' => ['title' => 'Mining', 'slug' => 'mining', 'summary' => 'Polyurethane products and industrial applications for mining operations.'],
                ],
            ],
            [
                'icon' => 'subsea',
                'sort_order' => 3,
                'is_featured' => true,
                'translations' => [
                    'ar' => ['title' => 'تحت البحرية', 'slug' => 'subsea', 'summary' => 'حلول تحت البحرية للبنية التحتية البحرية والأنابيب.'],
                    'en' => ['title' => 'Subsea', 'slug' => 'subsea', 'summary' => 'Subsea solutions for offshore infrastructure and pipelines.'],
                ],
            ],
            [
                'icon' => 'petrochemical',
                'sort_order' => 4,
                'is_featured' => true,
                'translations' => [
                    'ar' => ['title' => 'البتروكيماويات', 'slug' => 'petrochemicals', 'summary' => 'تصنيع ومكونات للمنشآت البتروكيماوية والمصافي.'],
                    'en' => ['title' => 'Petrochemicals', 'slug' => 'petrochemicals', 'summary' => 'Manufacturing and components for petrochemical plants and refineries.'],
                ],
            ],
        ];

        foreach ($industries as $data) {
            $translations = $data['translations'];
            unset($data['translations']);

            $slug = $translations['en']['slug'];
            $existing = IndustryTranslation::query()->where('slug', $slug)->where('locale', 'en')->first();

            $industry = $existing?->industry ?? new Industry;
            $industry->fill(array_merge($data, [
                'is_published' => true,
                'published_at' => $industry->published_at ?? now(),
            ]));
            $industry->save();

            foreach ($translations as $locale => $fields) {
                IndustryTranslation::query()->updateOrCreate(
                    ['industry_id' => $industry->id, 'locale' => $locale],
                    $fields,
                );
            }
        }
    }
}
