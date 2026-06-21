<?php

namespace Database\Seeders;

use App\Models\Industry;
use App\Models\IndustryTranslation;
use Illuminate\Database\Seeder;

/**
 * Seeds the industries / sectors carousel on the homepage.
 *
 * Run: php artisan db:seed --class=IndustrySeeder
 */
class IndustrySeeder extends Seeder
{
    public function run(): void
    {
        $slugs = [];

        foreach (self::definition() as $data) {
            $translations = $data['translations'];
            unset($data['translations']);

            $slug = $translations['en']['slug'];
            $slugs[] = $slug;

            $existing = IndustryTranslation::query()
                ->where('slug', $slug)
                ->where('locale', 'en')
                ->first();

            $industry = $existing?->industry ?? Industry::query()->firstOrNew(['uuid' => $data['uuid']]);
            $industry->fill(array_merge($data, [
                'uuid' => $industry->uuid ?? $data['uuid'],
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

        Industry::query()
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
                'uuid' => 'seed-oil-gas',
                'icon' => 'bi-fuel-pump-fill',
                'sort_order' => 1,
                'is_featured' => true,
                'translations' => [
                    'ar' => [
                        'title' => 'النفط والغاز',
                        'slug' => 'oil-gas',
                        'summary' => 'أنابيب تحت ضغط مستمر، بيئات تشغيل قاسية، وتوقف يُكلّف ملايين. حلول IKS مُصمَّمة لهذه البيئة تحديداً.',
                    ],
                    'en' => [
                        'title' => 'Oil & Gas',
                        'slug' => 'oil-gas',
                        'summary' => 'Pipelines under constant pressure, harsh operating environments, and downtime that costs millions. IKS solutions are engineered specifically for this sector.',
                    ],
                ],
            ],
            [
                'uuid' => 'seed-pipelines',
                'icon' => 'bi-diagram-3-fill',
                'sort_order' => 2,
                'is_featured' => true,
                'translations' => [
                    'ar' => [
                        'title' => 'خطوط الأنابيب',
                        'slug' => 'pipelines',
                        'summary' => 'من الصيانة الدورية إلى التنقيب الطارئ — لدينا المنتج والفريق الجاهز.',
                    ],
                    'en' => [
                        'title' => 'Pipelines',
                        'slug' => 'pipelines',
                        'summary' => 'From routine maintenance to emergency pigging — we have the product and the team ready.',
                    ],
                ],
            ],
            [
                'uuid' => 'seed-energy',
                'icon' => 'bi-lightning-charge-fill',
                'sort_order' => 3,
                'is_featured' => true,
                'translations' => [
                    'ar' => [
                        'title' => 'قطاع الطاقة',
                        'slug' => 'energy',
                        'summary' => 'مشاريع البنية التحتية الكبرى تحتاج مورداً يفهم حجم المسؤولية. نحن نفهمه.',
                    ],
                    'en' => [
                        'title' => 'Energy Sector',
                        'slug' => 'energy',
                        'summary' => 'Major infrastructure projects need a supplier who understands the scale of responsibility. We do.',
                    ],
                ],
            ],
            [
                'uuid' => 'seed-marine',
                'icon' => 'bi-water',
                'sort_order' => 4,
                'is_featured' => true,
                'translations' => [
                    'ar' => [
                        'title' => 'الصناعات البحرية',
                        'slug' => 'marine',
                        'summary' => 'بيئات التآكل المالحي تتطلب مواد استثنائية — منتجاتنا غير المعدنية مُصمَّمة لها.',
                    ],
                    'en' => [
                        'title' => 'Marine Industries',
                        'slug' => 'marine',
                        'summary' => 'Corrosive saltwater environments demand exceptional materials — our non-metallic products are built for them.',
                    ],
                ],
            ],
            [
                'uuid' => 'seed-mining-heavy-industry',
                'icon' => 'bi-hammer',
                'sort_order' => 5,
                'is_featured' => true,
                'translations' => [
                    'ar' => [
                        'title' => 'التعدين والصناعة الثقيلة',
                        'slug' => 'mining-heavy-industry',
                        'summary' => 'معدات صلبة، ظروف قاسية، ومتطلبات تقنية محددة. نُوفّر الحل المخصص.',
                    ],
                    'en' => [
                        'title' => 'Mining & Heavy Industry',
                        'slug' => 'mining-heavy-industry',
                        'summary' => 'Tough equipment, harsh conditions, and precise technical requirements. We deliver the custom solution.',
                    ],
                ],
            ],
            [
                'uuid' => 'seed-major-industrial-projects',
                'icon' => 'bi-building-fill',
                'sort_order' => 6,
                'is_featured' => true,
                'translations' => [
                    'ar' => [
                        'title' => 'المشاريع الصناعية الكبرى',
                        'slug' => 'major-industrial-projects',
                        'summary' => 'حين يكون الجدول الزمني ضيقاً والمتطلبات دقيقة — IKS الشريك الذي تحتاجه.',
                    ],
                    'en' => [
                        'title' => 'Major Industrial Projects',
                        'slug' => 'major-industrial-projects',
                        'summary' => 'When timelines are tight and requirements are exacting — IKS is the partner you need.',
                    ],
                ],
            ],
        ];
    }
}
