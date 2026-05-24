<?php

namespace Database\Seeders;

use App\Models\Gallery;
use App\Models\GalleryItem;
use App\Models\GalleryItemTranslation;
use App\Models\GalleryTranslation;
use Illuminate\Database\Seeder;

class GallerySeeder extends Seeder
{
    public function run(): void
    {
        $gallery = Gallery::query()->firstOrCreate(
            ['key' => 'main'],
            [
                'is_published' => true,
                'published_at' => now(),
                'sort_order' => 1,
            ],
        );

        GalleryTranslation::query()->updateOrCreate(
            ['gallery_id' => $gallery->id, 'locale' => 'ar'],
            [
                'title' => 'معرض الشركة',
                'description' => 'صور ومقاطع من منشآتنا ومشاريعنا.',
            ],
        );

        GalleryTranslation::query()->updateOrCreate(
            ['gallery_id' => $gallery->id, 'locale' => 'en'],
            [
                'title' => 'Company Gallery',
                'description' => 'Photos and videos from our facilities and projects.',
            ],
        );

        $items = [
            [
                'media_type' => 'video_youtube',
                'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'sort_order' => 0,
                'translations' => [
                    'ar' => ['title' => 'جولة في المنشأة', 'caption' => 'فيديو تعريفي بالمصنع.'],
                    'en' => ['title' => 'Facility tour', 'caption' => 'Introductory factory video.'],
                ],
            ],
            [
                'media_type' => 'image',
                'sort_order' => 1,
                'translations' => [
                    'ar' => ['title' => 'خط الإنتاج', 'caption' => 'تصنيع منتجات كشط الأنابيب.'],
                    'en' => ['title' => 'Production line', 'caption' => 'Pipeline scraping product manufacturing.'],
                ],
            ],
            [
                'media_type' => 'image',
                'sort_order' => 2,
                'translations' => [
                    'ar' => ['title' => 'مشروع ميداني', 'caption' => 'تنفيذ في المنطقة الشرقية.'],
                    'en' => ['title' => 'Field project', 'caption' => 'Deployment in the Eastern Province.'],
                ],
            ],
        ];

        foreach ($items as $index => $data) {
            $translations = $data['translations'];
            unset($data['translations']);

            $item = GalleryItem::query()->firstOrCreate(
                [
                    'gallery_id' => $gallery->id,
                    'sort_order' => $data['sort_order'] ?? $index,
                    'media_type' => $data['media_type'],
                ],
                array_merge($data, ['is_published' => true]),
            );

            foreach ($translations as $locale => $fields) {
                GalleryItemTranslation::query()->updateOrCreate(
                    ['gallery_item_id' => $item->id, 'locale' => $locale],
                    $fields,
                );
            }
        }
    }
}
