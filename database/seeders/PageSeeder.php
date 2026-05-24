<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\PageTranslation;
use App\Models\SeoMeta;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        $page = Page::query()->updateOrCreate(
            ['key' => 'about'],
            [
                'template' => 'default',
                'is_published' => true,
                'published_at' => now(),
                'sort_order' => 1,
            ],
        );

        PageTranslation::query()->updateOrCreate(
            ['page_id' => $page->id, 'locale' => 'ar'],
            [
                'title' => 'من نحن',
                'slug' => 'about-us',
                'excerpt' => 'شركة تصنيع سعودية متخصصة في حلول خطوط الأنابيب والتدخل التقني.',
                'body' => '<p>شركة IK السعودية للصناعات (IKS) شركة تصنيع سعودية تضامنية تمتلك منشأة في المدينة الصناعية الثانية بالدمام. رؤيتنا أن نكون الخيار الأول لحلول تدخل خطوط الأنابيب والتقنيات في السعودية والشرق الأوسط.</p><p>نقدم منتجات وخدمات كشط خطوط الأنابيب لقطاع النفط والغاز، بالإضافة إلى منتجات البولي يوريثان للتطبيقات تحت البحرية وغير المعدنية في التعدين.</p>',
            ],
        );

        PageTranslation::query()->updateOrCreate(
            ['page_id' => $page->id, 'locale' => 'en'],
            [
                'title' => 'About Us',
                'slug' => 'about-us',
                'excerpt' => 'A Saudi manufacturing company specializing in pipeline intervention solutions.',
                'body' => '<p>IK Saudi (IKS) for Industries is a Saudi JV-based company with a manufacturing facility at Dammam 2nd Industrial City. Our vision is to be the premier pipe and pipeline intervention solutions provider in Saudi Arabia and the Middle East.</p><p>We provide pipeline scraping products and services to the Oil &amp; Gas industry, as well as polyurethane-based products for non-metallic subsea and mining applications.</p>',
            ],
        );

        foreach (['ar', 'en'] as $locale) {
            SeoMeta::query()->updateOrCreate(
                [
                    'seoable_type' => Page::class,
                    'seoable_id' => $page->id,
                    'locale' => $locale,
                ],
                [
                    'meta_title' => $locale === 'ar'
                        ? 'من نحن | الشركة السعودية للصناعات'
                        : 'About Us | IK Saudi For Industries',
                    'meta_description' => $locale === 'ar'
                        ? 'تعرف على شركة IK السعودية للصناعات — تصنيع حلول خطوط الأنابيب في الدمام.'
                        : 'Learn about IK Saudi For Industries — pipeline manufacturing in Dammam, Saudi Arabia.',
                ],
            );
        }
    }
}
