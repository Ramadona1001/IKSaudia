<?php

namespace Database\Seeders;

use App\Models\Certification;
use App\Models\CertificationTranslation;
use Illuminate\Database\Seeder;

class CertificationSeeder extends Seeder
{
    public function run(): void
    {
        $certs = [
            [
                'issuer' => 'ISO',
                'sort_order' => 1,
                'is_featured' => true,
                'translations' => [
                    'ar' => ['title' => 'ISO 9001', 'slug' => 'iso-9001', 'description' => 'نظام إدارة الجودة'],
                    'en' => ['title' => 'ISO 9001', 'slug' => 'iso-9001', 'description' => 'Quality management system'],
                ],
            ],
            [
                'issuer' => 'ASME',
                'sort_order' => 2,
                'is_featured' => true,
                'translations' => [
                    'ar' => ['title' => 'ASME', 'slug' => 'asme', 'description' => 'معايير المعدات والغلايات'],
                    'en' => ['title' => 'ASME', 'slug' => 'asme', 'description' => 'Pressure equipment standards'],
                ],
            ],
            [
                'issuer' => 'API',
                'sort_order' => 3,
                'is_featured' => true,
                'translations' => [
                    'ar' => ['title' => 'API', 'slug' => 'api', 'description' => 'معايير قطاع النفط والغاز'],
                    'en' => ['title' => 'API', 'slug' => 'api', 'description' => 'Oil & Gas industry standards'],
                ],
            ],
            [
                'issuer' => 'ASTM',
                'sort_order' => 4,
                'is_featured' => true,
                'translations' => [
                    'ar' => ['title' => 'ASTM', 'slug' => 'astm', 'description' => 'مواصفات المواد والاختبارات'],
                    'en' => ['title' => 'ASTM', 'slug' => 'astm', 'description' => 'Materials and testing specifications'],
                ],
            ],
        ];

        foreach ($certs as $data) {
            $translations = $data['translations'];
            unset($data['translations']);

            $slug = $translations['en']['slug'];
            $existing = CertificationTranslation::query()->where('slug', $slug)->where('locale', 'en')->first();

            $cert = $existing?->certification ?? new Certification;
            $cert->fill(array_merge($data, [
                'is_published' => true,
                'published_at' => $cert->published_at ?? now(),
            ]));
            $cert->save();

            foreach ($translations as $locale => $fields) {
                CertificationTranslation::query()->updateOrCreate(
                    ['certification_id' => $cert->id, 'locale' => $locale],
                    $fields,
                );
            }
        }
    }
}
