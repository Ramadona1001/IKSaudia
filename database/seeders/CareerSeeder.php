<?php

namespace Database\Seeders;

use App\Models\Career;
use App\Models\CareerTranslation;
use Illuminate\Database\Seeder;

class CareerSeeder extends Seeder
{
    public function run(): void
    {
        $careers = [
            [
                'department' => 'Engineering',
                'location' => 'Dammam',
                'employment_type' => 'full_time',
                'experience_level' => 'senior',
                'sort_order' => 1,
                'translations' => [
                    'ar' => [
                        'title' => 'مهندس تدخل خطوط الأنابيب',
                        'slug' => 'pipeline-intervention-engineer',
                        'summary' => 'قيادة فرق التدخل الفني في مشاريع النفط والغاز.',
                        'requirements' => '<ul><li>بكالوريوس هندسة ميكانيكية أو بترول</li><li>خبرة 5+ سنوات</li></ul>',
                        'responsibilities' => '<ul><li>تخطيط عمليات التدخل</li><li>الإشراف الميداني</li></ul>',
                        'benefits' => '<ul><li>تأمين طبي</li><li>بدل سكن</li></ul>',
                    ],
                    'en' => [
                        'title' => 'Pipeline Intervention Engineer',
                        'slug' => 'pipeline-intervention-engineer',
                        'summary' => 'Lead technical intervention teams on oil and gas projects.',
                        'requirements' => '<ul><li>BSc in Mechanical or Petroleum Engineering</li><li>5+ years experience</li></ul>',
                        'responsibilities' => '<ul><li>Plan intervention operations</li><li>Field supervision</li></ul>',
                        'benefits' => '<ul><li>Medical insurance</li><li>Housing allowance</li></ul>',
                    ],
                ],
            ],
            [
                'department' => 'Manufacturing',
                'location' => 'Dammam',
                'employment_type' => 'full_time',
                'experience_level' => 'mid',
                'sort_order' => 2,
                'translations' => [
                    'ar' => [
                        'title' => 'فني تصنيع',
                        'slug' => 'manufacturing-technician',
                        'summary' => 'تشغيل وصيانة خطوط إنتاج منتجات البولي يوريثان.',
                        'requirements' => '<ul><li>دبلوم تقني</li><li>خبرة في التصنيع</li></ul>',
                        'responsibilities' => '<ul><li>تشغيل المعدات</li><li>ضبط الجودة</li></ul>',
                        'benefits' => '<ul><li>تدريب مستمر</li></ul>',
                    ],
                    'en' => [
                        'title' => 'Manufacturing Technician',
                        'slug' => 'manufacturing-technician',
                        'summary' => 'Operate and maintain polyurethane product production lines.',
                        'requirements' => '<ul><li>Technical diploma</li><li>Manufacturing experience</li></ul>',
                        'responsibilities' => '<ul><li>Operate equipment</li><li>Quality control</li></ul>',
                        'benefits' => '<ul><li>Continuous training</li></ul>',
                    ],
                ],
            ],
            [
                'department' => 'Sales',
                'location' => 'Riyadh',
                'employment_type' => 'full_time',
                'experience_level' => 'mid',
                'is_remote' => false,
                'sort_order' => 3,
                'translations' => [
                    'ar' => [
                        'title' => 'مدير مبيعات قطاع النفط والغاز',
                        'slug' => 'oil-gas-sales-manager',
                        'summary' => 'تطوير علاقات العملاء في قطاع الطاقة.',
                        'requirements' => '<ul><li>خبرة مبيعات B2B</li><li>إتقان العربية والإنجليزية</li></ul>',
                        'responsibilities' => '<ul><li>إدارة محفظة العملاء</li><li>إعداد العروض الفنية</li></ul>',
                        'benefits' => '<ul><li>عمولة أداء</li></ul>',
                    ],
                    'en' => [
                        'title' => 'Oil & Gas Sales Manager',
                        'slug' => 'oil-gas-sales-manager',
                        'summary' => 'Develop client relationships in the energy sector.',
                        'requirements' => '<ul><li>B2B sales experience</li><li>Fluent Arabic and English</li></ul>',
                        'responsibilities' => '<ul><li>Manage client portfolio</li><li>Prepare technical proposals</li></ul>',
                        'benefits' => '<ul><li>Performance commission</li></ul>',
                    ],
                ],
            ],
        ];

        foreach ($careers as $data) {
            $translations = $data['translations'];
            unset($data['translations']);

            $slug = $translations['en']['slug'];
            $existing = CareerTranslation::query()
                ->where('slug', $slug)
                ->where('locale', 'en')
                ->first();

            $career = $existing?->career ?? new Career;
            $career->fill(array_merge($data, [
                'uuid' => $career->uuid ?? substr('cr-'.$slug, 0, 36),
                'is_published' => true,
                'published_at' => $career->published_at ?? now(),
                'closes_at' => $career->closes_at ?? now()->addMonths(3),
            ]));
            $career->save();

            foreach ($translations as $locale => $fields) {
                CareerTranslation::query()->updateOrCreate(
                    ['career_id' => $career->id, 'locale' => $locale],
                    $fields,
                );
            }
        }
    }
}
