<?php

namespace Database\Seeders;

use App\Models\NewsPost;
use App\Models\NewsPostTranslation;
use App\Models\User;
use Illuminate\Database\Seeder;

class NewsPostSeeder extends Seeder
{
    public function run(): void
    {
        $authorId = User::query()->where('email', 'admin@iksaudi.com')->value('id');

        $posts = [
            [
                'is_featured' => true,
                'translations' => [
                    'ar' => [
                        'title' => 'توسعة منشأة الدمام',
                        'slug' => 'dammam-facility-expansion',
                        'excerpt' => 'إطلاق مرحلة جديدة من التوسعة في المدينة الصناعية الثانية بالدمام.',
                        'body' => '<p>أعلنت الشركة السعودية للصناعات عن بدء مرحلة توسعة في منشأتها بالدمام لزيادة الطاقة الإنتاجية لمنتجات كشط خطوط الأنابيب.</p>',
                    ],
                    'en' => [
                        'title' => 'Dammam Facility Expansion',
                        'slug' => 'dammam-facility-expansion',
                        'excerpt' => 'A new expansion phase at our Dammam 2nd Industrial City facility.',
                        'body' => '<p>IK Saudi For Industries announced a new expansion phase at its Dammam facility to increase production capacity for pipeline scraping products.</p>',
                    ],
                ],
            ],
            [
                'is_featured' => false,
                'translations' => [
                    'ar' => [
                        'title' => 'شراكة استراتيجية جديدة',
                        'slug' => 'new-strategic-partnership',
                        'excerpt' => 'توقيع اتفاقية تعاون مع شريك تقني عالمي.',
                        'body' => '<p>وقّعت الشركة اتفاقية تعاون استراتيجي لتعزيز قدرات التصنيع والتوريد في قطاع النفط والغاز.</p>',
                    ],
                    'en' => [
                        'title' => 'New Strategic Partnership',
                        'slug' => 'new-strategic-partnership',
                        'excerpt' => 'Signing a cooperation agreement with a global technology partner.',
                        'body' => '<p>The company signed a strategic cooperation agreement to strengthen manufacturing and supply capabilities in the oil and gas sector.</p>',
                    ],
                ],
            ],
            [
                'is_featured' => false,
                'translations' => [
                    'ar' => [
                        'title' => 'مشاركة في معرض الطاقة',
                        'slug' => 'energy-exhibition',
                        'excerpt' => 'عرض أحدث حلول البولي يوريثان تحت البحرية.',
                        'body' => '<p>شاركت الشركة في معرض الطاقة الإقليمي لعرض حلولها التقنية في تدخل خطوط الأنابيب والبولي يوريثان.</p>',
                    ],
                    'en' => [
                        'title' => 'Energy Exhibition Participation',
                        'slug' => 'energy-exhibition',
                        'excerpt' => 'Showcasing the latest subsea polyurethane solutions.',
                        'body' => '<p>The company participated in a regional energy exhibition to present pipeline intervention and polyurethane solutions.</p>',
                    ],
                ],
            ],
        ];

        foreach ($posts as $data) {
            $translations = $data['translations'];
            unset($data['translations']);

            $slug = $translations['en']['slug'];
            $existing = NewsPostTranslation::query()
                ->where('slug', $slug)
                ->where('locale', 'en')
                ->first();

            $post = $existing?->newsPost ?? new NewsPost;
            $post->fill(array_merge($data, [
                'uuid' => $post->uuid ?? substr('sn-'.$slug, 0, 36),
                'author_id' => $authorId,
                'is_published' => true,
                'published_at' => $post->published_at ?? now()->subDays(rand(5, 60)),
            ]));
            $post->save();

            foreach ($translations as $locale => $fields) {
                NewsPostTranslation::query()->updateOrCreate(
                    ['news_post_id' => $post->id, 'locale' => $locale],
                    $fields,
                );
            }
        }
    }
}
