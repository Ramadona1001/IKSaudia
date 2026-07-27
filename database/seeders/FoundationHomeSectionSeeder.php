<?php

namespace Database\Seeders;

use App\Models\HomeSection;
use App\Models\HomeSectionTranslation;
use App\Support\FoundationSection;
use Illuminate\Database\Seeder;

/**
 * Seeds Mission, Vision & Values (home_sections.key = foundation).
 *
 * Edit in admin: /ik-admin/mission-vision-values
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
                        'title' => 'الرؤية والرسالة و',
                        'highlight' => 'القيم',
                    ],
                    'en' => [
                        'eyebrow' => 'Our Foundation',
                        'title' => 'Vision, Mission & ',
                        'highlight' => 'Values',
                    ],
                ],
                'vision' => [
                    'ar' => [
                        'title' => 'رؤيتنا',
                        'description' => 'أن تكون IKS الاسم الأول الذي يتبادر إلى ذهن كل مهندس ومدير مشتريات في قطاع الطاقة والصناعة حين يبحث عن مورد يثق به — في السعودية والخليج والشرق الأوسط.',
                    ],
                    'en' => [
                        'title' => 'Our Vision',
                        'description' => 'To be the first name that comes to mind for every engineer and procurement manager in the energy and industrial sectors when they look for a supplier they can trust — in Saudi Arabia, the Gulf, and the Middle East.',
                    ],
                ],
                'mission' => [
                    'ar' => [
                        'title' => 'رسالتنا',
                        'description' => 'نُصنّع منتجات ونُقدّم حلولاً تُبقي مشاريع عملائنا تعمل دون انقطاع — لأن توقف مشروعك ليس خياراً، وإيجاد الحل السريع هو مسؤوليتنا.',
                    ],
                    'en' => [
                        'title' => 'Our Mission',
                        'description' => "We manufacture products and deliver solutions that keep our clients' projects running without interruption — because downtime is not an option for you, and finding the fast solution is our responsibility.",
                    ],
                ],
                'values' => [
                    'ar' => [
                        'title' => 'قيمنا',
                        'description' => implode("\n\n", [
                            'الجودة: كل منتج يصلك موثَّق بشهادة مطابقة دولية. لا استثناءات.',
                            'الموثوقية: نلتزم بمواعيد التسليم لأننا نعرف أن تأخيرنا يعني تأخيراً في مشروعك.',
                            'السلامة: معايير السلامة الدولية ليست خياراً في منتجاتنا — هي الحد الأدنى الذي نبدأ منه.',
                            'الابتكار: حين لا يجد العميل ما يحتاجه في السوق، نُصنّعه له. هكذا وُلد الـ Non-Metallic Flange Shroud.',
                            'الشراكة: علاقتنا مع العميل لا تنتهي عند الفاتورة — تنتهي حين ينجح مشروعه.',
                        ]),
                    ],
                    'en' => [
                        'title' => 'Our Values',
                        'description' => implode("\n\n", [
                            'Quality: Every product you receive is documented with an international conformity certificate. No exceptions.',
                            'Reliability: We commit to delivery schedules because we know that our delay means a delay in your project.',
                            'Safety: International safety standards are not optional in our products — they are the minimum we start from.',
                            'Innovation: When a client cannot find what they need in the market, we manufacture it for them. That is how the Non-Metallic Flange Shroud came to be.',
                            'Partnership: Our relationship with the client does not end at the invoice — it ends when their project succeeds.',
                        ]),
                    ],
                ],
            ],
        ];
    }
}
