<?php

namespace Database\Seeders;

use App\Models\HomeSection;
use App\Models\HomeSectionTranslation;
use Illuminate\Database\Seeder;

class HomeContentSeeder extends Seeder
{
    public function run(): void
    {
        $sections = [
            [
                'key' => 'hero',
                'type' => 'hero',
                'sort_order' => 1,
                'translations' => [
                    'ar' => [
                        'title' => 'الشركة السعودية للصناعات',
                        'subtitle' => 'حلول أنابيب وخطوط الأنابيب للنفط والغاز والتعدين في المملكة والمنطقة',
                        'cta_label' => 'اعرف المزيد',
                        'cta_url' => '#about',
                    ],
                    'en' => [
                        'title' => 'IK Saudi For Industries',
                        'subtitle' => 'Pipeline intervention, scraping & polyurethane solutions for Oil & Gas and mining',
                        'cta_label' => 'Learn more',
                        'cta_url' => '#about',
                    ],
                ],
            ],
            [
                'key' => 'services_grid',
                'type' => 'services_grid',
                'sort_order' => 3,
                'translations' => [
                    'ar' => [
                        'title' => 'خدماتنا',
                        'subtitle' => 'حلول تصنيع وتدخل خطوط الأنابيب',
                    ],
                    'en' => [
                        'title' => 'Our Services',
                        'subtitle' => 'Manufacturing & pipeline intervention solutions',
                    ],
                ],
            ],
            [
                'key' => 'cta_contact',
                'type' => 'cta',
                'sort_order' => 4,
                'translations' => [
                    'ar' => [
                        'title' => 'أصول العملاء هي أولويتنا',
                        'subtitle' => 'تواصل مع فريقنا للاستفسارات الصناعية والتقنية',
                        'cta_label' => 'اتصل بنا',
                        'cta_url' => '/ar/contact',
                    ],
                    'en' => [
                        'title' => 'Client assets are our top priority',
                        'subtitle' => 'Reach our team for industrial and technical enquiries',
                        'cta_label' => 'Contact us',
                        'cta_url' => '/en/contact',
                    ],
                ],
            ],
        ];

        foreach ($sections as $data) {
            $translations = $data['translations'];
            unset($data['translations']);

            $section = HomeSection::query()->updateOrCreate(
                ['key' => $data['key']],
                array_merge($data, ['is_active' => true]),
            );

            foreach ($translations as $locale => $fields) {
                HomeSectionTranslation::query()->updateOrCreate(
                    ['home_section_id' => $section->id, 'locale' => $locale],
                    $fields,
                );
            }
        }

        $this->call(AboutSnippetHomeSectionSeeder::class);
        $this->call(FoundationHomeSectionSeeder::class);
    }
}
