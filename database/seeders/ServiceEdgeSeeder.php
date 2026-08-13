<?php

namespace Database\Seeders;

use App\Models\ServiceEdge;
use Illuminate\Database\Seeder;

class ServiceEdgeSeeder extends Seeder
{
    public function run(): void
    {
        if (ServiceEdge::query()->exists()) {
            return;
        }

        $items = [
            [
                'icon' => 'bi-patch-check-fill',
                'title' => [
                    'en' => 'Aramco Approved',
                    'ar' => 'معتمد من أرامكو',
                ],
                'description' => [
                    'en' => "Registered on Saudi Aramco's Approved Vendor List (AVL) — your guarantee of quality.",
                    'ar' => 'مسجل في قائمة الموردين المعتمدين من أرامكو السعودية (AVL) — ضمانك للجودة.',
                ],
            ],
            [
                'icon' => 'bi-people-fill',
                'title' => [
                    'en' => 'Expert Workforce',
                    'ar' => 'قوة عاملة متخصصة',
                ],
                'description' => [
                    'en' => 'Thousands of skilled professionals with deep experience in Saudi industrial projects.',
                    'ar' => 'آلاف من المتخصصين ذوي الخبرة العميقة في المشاريع الصناعية السعودية.',
                ],
            ],
            [
                'icon' => 'bi-shield-fill-check',
                'title' => [
                    'en' => 'Zero Compromise Safety',
                    'ar' => 'سلامة بلا تنازلات',
                ],
                'description' => [
                    'en' => 'Industry-leading LTIFR safety record — because every worker goes home safely every day.',
                    'ar' => 'سجل سلامة LTIFR رائد في الصناعة — لأن كل عامل يعود إلى المنزل بأمان كل يوم.',
                ],
            ],
            [
                'icon' => 'bi-graph-up-arrow',
                'title' => [
                    'en' => 'On-Time Delivery',
                    'ar' => 'تسليم في الموعد',
                ],
                'description' => [
                    'en' => '96% on-time project delivery rate — enabled by rigorous planning and proactive risk management.',
                    'ar' => 'معدل تسليم 96% في الموعد المحدد — ممكّن بالتخطيط الدقيق وإدارة المخاطر الاستباقية.',
                ],
            ],
        ];

        foreach ($items as $index => $item) {
            $edge = ServiceEdge::query()->create([
                'icon' => $item['icon'],
                'is_published' => true,
                'sort_order' => $index,
            ]);

            foreach (['ar', 'en'] as $locale) {
                $edge->translations()->create([
                    'locale' => $locale,
                    'title' => $item['title'][$locale],
                    'description' => $item['description'][$locale],
                ]);
            }
        }
    }
}
