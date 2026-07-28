<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\ServiceTranslation;
use Illuminate\Database\Seeder;

/**
 * Seeds the four core service categories shown on the homepage and services catalog.
 *
 * Run: php artisan db:seed --class=ServiceSeeder
 */
class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $slugs = [];

        foreach (self::definition() as $data) {
            $translations = $data['translations'];
            unset($data['translations']);

            $slug = $translations['en']['slug'];
            $slugs[] = $slug;

            $service = Service::query()->updateOrCreate(
                ['uuid' => $data['uuid']],
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

        Service::query()
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
                'uuid' => 'seed-industrial-manufacturing',
                'sort_order' => 1,
                'icon' => 'bi-factory',
                'translations' => [
                    'ar' => [
                        'title' => 'التصنيع الصناعي',
                        'slug' => 'industrial-manufacturing',
                        'summary' => 'تصميم وإنتاج منتجات صناعية متخصصة بمواصفات دقيقة ومعايير جودة دولية.',
                        'body' => self::bulletsHtml([
                            'تصميم وإنتاج منتجات صناعية متخصصة',
                            'مواصفات دقيقة ومعايير جودة دولية',
                            'توثيق فني كامل لكل وحدة',
                        ]),
                    ],
                    'en' => [
                        'title' => 'Industrial Manufacturing',
                        'slug' => 'industrial-manufacturing',
                        'summary' => 'Design and production of specialized industrial products with precise specifications and international quality standards.',
                        'body' => self::bulletsHtml([
                            'Design and production of specialized industrial products',
                            'Precise specifications and international quality standards',
                            'Complete technical documentation for every unit',
                        ]),
                    ],
                ],
            ],
            [
                'uuid' => 'seed-industrial-trading',
                'sort_order' => 2,
                'icon' => 'bi-truck',
                'translations' => [
                    'ar' => [
                        'title' => 'التوريد الصناعي',
                        'slug' => 'industrial-trading',
                        'summary' => 'توريد المواد والمعدات في الوقت المحدد مع الالتزام بالمواصفات المطلوبة.',
                        'body' => self::bulletsHtml([
                            'توريد المواد والمعدات في الوقت المحدد',
                            'الالتزام بالمواصفات المطلوبة',
                            'دعم لوجستي ميداني سريع',
                        ]),
                    ],
                    'en' => [
                        'title' => 'Industrial Trading',
                        'slug' => 'industrial-trading',
                        'summary' => 'On-time supply of materials and equipment with full compliance to required specifications.',
                        'body' => self::bulletsHtml([
                            'On-time supply of materials and equipment',
                            'Compliance with required specifications',
                            'Rapid on-site logistics support',
                        ]),
                    ],
                ],
            ],
            [
                'uuid' => 'seed-technical-support-services',
                'sort_order' => 3,
                'icon' => 'bi-tools',
                'translations' => [
                    'ar' => [
                        'title' => 'الدعم الفني والخدمات الهندسية',
                        'slug' => 'technical-support-services',
                        'summary' => 'استشارات هندسية متخصصة ودعم ميداني خلال دورة حياة المشروع.',
                        'body' => self::bulletsHtml([
                            'استشارات هندسية متخصصة',
                            'دعم ميداني خلال دورة حياة المشروع',
                            'متابعة التشغيل والصيانة',
                        ]),
                    ],
                    'en' => [
                        'title' => 'Technical Support & Engineering Services',
                        'slug' => 'technical-support-services',
                        'summary' => 'Specialized engineering consultancy and field support throughout the project lifecycle.',
                        'body' => self::bulletsHtml([
                            'Specialized engineering consultancy',
                            'Field support throughout the project lifecycle',
                            'Operations and maintenance follow-up',
                        ]),
                    ],
                ],
            ],
            [
                'uuid' => 'seed-custom-solutions',
                'sort_order' => 4,
                'icon' => 'bi-puzzle',
                'translations' => [
                    'ar' => [
                        'title' => 'الحلول المخصصة',
                        'slug' => 'custom-solutions',
                        'summary' => 'تصميم حلول هندسية مخصصة حسب بيئة المشروع وظروف التشغيل.',
                        'body' => self::bulletsHtml([
                            'تصميم حلول حسب بيئة المشروع وظروف التشغيل',
                            'التعامل مع أنابيب بضغط أو منحنيات غير قياسية',
                            'إعداد حلول هندسية مخصصة لتقليل الأعطال وزيادة الكفاءة',
                        ]),
                    ],
                    'en' => [
                        'title' => 'Custom Solutions',
                        'slug' => 'custom-solutions',
                        'summary' => 'Tailored engineering solutions designed for your project environment and operating conditions.',
                        'body' => self::bulletsHtml([
                            'Solutions designed for project environment and operating conditions',
                            'Handling pressurized pipelines and non-standard bends',
                            'Custom engineering solutions to reduce failures and increase efficiency',
                        ]),
                    ],
                ],
            ],
        ];
    }

    /**
     * @param  list<string>  $items
     */
    private static function bulletsHtml(array $items): string
    {
        $lis = collect($items)
            ->map(fn (string $item): string => '<li>'.e($item).'</li>')
            ->implode('');

        return '<ul>'.$lis.'</ul>';
    }
}
