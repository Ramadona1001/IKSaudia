<?php

namespace Database\Seeders;

use App\Models\HomeSection;
use App\Models\HomeSectionTranslation;
use App\Support\AboutSectionStats;
use Illuminate\Database\Seeder;

/**
 * Seeds the homepage “About us” block (home_sections.key = about_snippet, usually id 2).
 *
 * Run: php artisan db:seed --class=AboutSnippetHomeSectionSeeder
 */
class AboutSnippetHomeSectionSeeder extends Seeder
{
    public function run(): void
    {
        $definition = self::definition();

        $section = HomeSection::query()->updateOrCreate(
            ['key' => $definition['key']],
            [
                'type' => $definition['type'],
                'sort_order' => $definition['sort_order'],
                'is_active' => true,
                'settings' => $definition['settings'],
            ],
        );

        foreach ($definition['translations'] as $locale => $fields) {
            HomeSectionTranslation::query()->updateOrCreate(
                ['home_section_id' => $section->id, 'locale' => $locale],
                $fields,
            );
        }
    }

    /**
     * @return array{
     *     key: string,
     *     type: string,
     *     sort_order: int,
     *     settings: array<string, mixed>,
     *     translations: array<string, array<string, string>>
     * }
     */
    public static function definition(): array
    {
        return [
            'key' => 'about_snippet',
            'type' => 'about_snippet',
            'sort_order' => 2,
            'settings' => [
                'years_badge' => [
                    'ar' => [
                        'count' => 25,
                        'suffix' => '+',
                        'label' => 'سنة من التميز',
                    ],
                    'en' => [
                        'count' => 25,
                        'suffix' => '+',
                        'label' => 'Years of Excellence',
                    ],
                ],
                'stats' => [
                    'ar' => AboutSectionStats::defaultStatsForLocale('ar'),
                    'en' => AboutSectionStats::defaultStatsForLocale('en'),
                ],
            ],
            'translations' => [
                'ar' => [
                    'title' => 'بناء مستقبل المملكة الصناعي',
                    'subtitle' => 'من نحن',
                    'content' => 'الشركة السعودية للصناعات (IKS) شركة سعودية تضامنية تمتلك منشأة تصنيع في المدينة الصناعية الثانية بالدمام. رؤيتنا أن نكون الخيار الأول لحلول تدخل خطوط الأنابيب والتقنيات في السعودية والشرق الأوسط. نقدم منتجات وخدمات كشط خطوط الأنابيب لقطاع النفط والغاز، بالإضافة إلى منتجات البولي يوريثان للتطبيقات تحت البحرية وغير المعدنية في التعدين.',
                    'cta_label' => 'اعرف المزيد',
                    'cta_url' => '/ar/about-us',
                ],
                'en' => [
                    'title' => "Building Saudi Arabia's Industrial Future",
                    'subtitle' => 'Who We Are',
                    'content' => 'IKS for Industries is a Saudi JV-based company with a manufacturing facility at Dammam 2nd Industrial City. Our vision is to be the premier pipe and pipeline intervention solutions provider in Saudi Arabia and the Middle East. We provide pipeline scraping products and services to the Oil & Gas industry, as well as polyurethane-based products for non-metallic subsea and mining applications.',
                    'cta_label' => 'Learn More',
                    'cta_url' => '/en/about-us',
                ],
            ],
        ];
    }
}
