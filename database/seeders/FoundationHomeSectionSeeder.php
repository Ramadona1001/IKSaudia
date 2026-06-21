<?php

namespace Database\Seeders;

use App\Models\HomeSection;
use App\Models\HomeSectionTranslation;
use App\Support\FoundationSection;
use Illuminate\Database\Seeder;

/**
 * Seeds Mission, Vision & Values (home_sections.key = foundation).
 *
 * Edit in admin: /ik-admin/home-sections → “foundation” section.
 *
 * Run: php artisan db:seed --class=FoundationHomeSectionSeeder
 */
class FoundationHomeSectionSeeder extends Seeder
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

        foreach (['ar', 'en'] as $locale) {
            HomeSectionTranslation::query()->updateOrCreate(
                ['home_section_id' => $section->id, 'locale' => $locale],
                [
                    'content' => FoundationSection::encodePayload(
                        FoundationSection::localePayloadFromSettings($definition['settings'], $locale),
                    ),
                ],
            );
        }
    }

    /**
     * @return array{
     *     key: string,
     *     type: string,
     *     sort_order: int,
     *     settings: array<string, mixed>
     * }
     */
    public static function definition(): array
    {
        return [
            'key' => 'foundation',
            'type' => 'foundation',
            'sort_order' => 3,
            'settings' => [
                'heading' => [
                    'ar' => [
                        'eyebrow' => 'أساسنا',
                        'title' => 'المهمة والرؤية و',
                        'highlight' => 'القيم',
                    ],
                    'en' => [
                        'eyebrow' => 'Our Foundation',
                        'title' => 'Mission, Vision & ',
                        'highlight' => 'Values',
                    ],
                ],
                'mission' => [
                    'ar' => [
                        'title' => 'مهمتنا',
                        'description' => 'تقديم التميز الهندسي الذي يدعم التحول الصناعي للمملكة وأهداف رؤية 2030.',
                    ],
                    'en' => [
                        'title' => 'Our Mission',
                        'description' => "To deliver engineering excellence that powers Saudi Arabia's industrial transformation and Vision 2030 goals.",
                    ],
                ],
                'vision' => [
                    'ar' => [
                        'title' => 'رؤيتنا',
                        'description' => 'أن نكون الشريك الصناعي الأكثر ثقة في منطقة الخليج، ونضع معايير الجودة والابتكار.',
                    ],
                    'en' => [
                        'title' => 'Our Vision',
                        'description' => "To be the GCC's most trusted industrial manufacturing partner, setting benchmarks for quality and innovation.",
                    ],
                ],
                'values' => [
                    'ar' => [
                        'title' => 'قيمنا',
                        'description' => 'الجودة أولاً · السلامة دائماً · النزاهة في كل شيء · مدفوع بالابتكار · محوره العميل · ملتزم بالتوطين · المسؤولية البيئية.',
                    ],
                    'en' => [
                        'title' => 'Our Values',
                        'description' => 'Quality First · Safety Always · Integrity in Everything · Innovation-Driven · Customer-Centric · Nationalization Committed · Environmental Responsibility.',
                    ],
                ],
            ],
        ];
    }
}
