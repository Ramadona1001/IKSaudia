<?php

namespace Database\Seeders;

use App\Services\SettingsService;
use Illuminate\Database\Seeder;

class SiteSettingsSeeder extends Seeder
{
    public function run(): void
    {
        app(SettingsService::class)->syncFromForm([
            'general' => [
                'site_name' => [
                    'ar' => 'الشركة السعودية للصناعات',
                    'en' => 'IK Saudi For Industries',
                ],
                'site_tagline' => [
                    'ar' => 'للصناعات',
                    'en' => 'For Industries',
                ],
                'default_locale' => 'ar',
                'supported_locales' => ['ar', 'en'],
                'favicon' => null,
                'logo' => null,
                'logo_sticky' => null,
                'logo_footer' => null,
                'logo_dark' => null,
                'seo_default_image' => null,
            ],
            'branding' => [
                'primary_color' => '#0c1f38',
                'secondary_color' => '#1a3d66',
                'accent_color' => '#c8922a',
                'page_bg_color' => '#0c1f38',
                'hero_text_color' => '#ffffff',
                'header_bg_color' => '#ffffff',
                'header_text_color' => '#0c1f38',
                'header_text_hover_color' => '#1a3d66',
                'header_icon_bg_color' => '#1a3d66',
                'footer_bg_color' => '#030710',
                'footer_text_color' => '#ffffff',
                'footer_accent_color' => '#c8922a',
                'font_latin' => 'Inter',
                'font_arabic' => 'IBM Plex Sans Arabic',
                'hero_background_image' => null,
                'hero_background_video' => null,
                'page_hero_pattern' => 'hexagon',
                'page_hero_pattern_image' => null,
                'page_hero_pattern_size' => 60,
                'page_hero_pattern_opacity' => 25,
                'loading_logo' => null,
            ],
            'footer' => [
                'description' => [
                    'ar' => 'شركة تصنيع سعودية متخصصة في حلول تدخل خطوط الأنابيب والبولي يوريثان للتطبيقات الصناعية.',
                    'en' => 'Saudi manufacturer specializing in pipeline intervention and polyurethane solutions for industrial applications.',
                ],
                'copyright' => [
                    'ar' => 'الشركة السعودية للصناعات',
                    'en' => 'IK Saudi For Industries',
                ],
                'quick_links' => [],
                'service_links' => [],
                'industry_links' => [],
                'legal_links' => [
                    ['label_ar' => 'الخصوصية', 'label_en' => 'Privacy', 'url' => 'privacy-policy', 'is_visible' => true, 'sort_order' => 1],
                    ['label_ar' => 'الشروط', 'label_en' => 'Terms', 'url' => 'terms-of-use', 'is_visible' => true, 'sort_order' => 2],
                ],
                'certification_badges' => [
                    ['code' => 'ISO', 'label' => 'ISO', 'enabled' => true],
                    ['code' => 'ASME', 'label' => 'ASME', 'enabled' => true],
                    ['code' => 'API', 'label' => 'API', 'enabled' => true],
                    ['code' => 'ASTM', 'label' => 'ASTM', 'enabled' => true],
                ],
                'cta_enabled' => true,
                'cta_overline' => ['ar' => 'لنبدأ مشروعك', 'en' => 'Start your project'],
                'cta_title' => ['ar' => 'أصول العملاء هي أولويتنا', 'en' => 'Client assets are our top priority'],
                'cta_subtitle' => [
                    'ar' => 'شريك صناعي سعودي بمعايير عالمية — من الاستشارة إلى التنفيذ الميداني.',
                    'en' => 'A Saudi industrial partner built to global standards — from consultation to field execution.',
                ],
                'background_image' => null,
            ],
            'contact' => [
                'address' => [
                    'ar' => "الطريق 122 × 23\nالمدينة الصناعية الثانية\nمبنى 3744، الدمام 34325",
                    'en' => "Road 122 by 23\n2nd Industrial City, Bldg. 3744\nDammam 34325\nKingdom of Saudi Arabia",
                ],
                'maps_embed' => null,
                'phones' => [
                    ['label' => 'Office', 'number' => '+966138095254', 'is_primary' => true],
                    ['label' => 'Mobile', 'number' => '+966559353880', 'is_primary' => false],
                ],
                'whatsapp' => '+966559353880',
                'emails' => [
                    ['label' => 'General', 'address' => 'info@iksaudi.com', 'is_primary' => true],
                ],
                'working_hours' => [
                    'ar' => 'الأحد – الخميس: 8:00 – 17:00',
                    'en' => 'Sunday – Thursday: 8:00 AM – 5:00 PM',
                ],
                'emergency_phone' => '+966138095254',
                'form_recipients' => [
                    ['email' => 'info@iksaudi.com'],
                ],
            ],
            'social' => [
                'links' => [
                    ['platform' => 'linkedin', 'url' => 'https://www.linkedin.com/company/iksforindustries', 'enabled' => true, 'label' => 'LinkedIn'],
                    ['platform' => 'x', 'url' => '', 'enabled' => false],
                ],
            ],
            'newsletter' => [
                'enabled' => false,
                'title' => ['ar' => 'النشرة الإخبارية', 'en' => 'Newsletter'],
                'description' => ['ar' => '', 'en' => ''],
                'mailchimp_api_key' => null,
                'mailchimp_list_id' => null,
                'mailchimp_server' => null,
                'cta_text' => ['ar' => 'اشترك', 'en' => 'Subscribe'],
            ],
            'seo' => [
                'default_meta_title' => [
                    'ar' => 'الشركة السعودية للصناعات',
                    'en' => 'IK Saudi For Industries',
                ],
                'default_meta_description' => [
                    'ar' => 'تصنيع صناعي وخدمات تقنية لقطاع الطاقة في المملكة العربية السعودية.',
                    'en' => 'Industrial manufacturing and technical services for Saudi Arabia\'s energy sector.',
                ],
                'default_keywords' => ['ar' => '', 'en' => ''],
                'og_image' => null,
                'twitter_card' => 'summary_large_image',
                'twitter_site' => null,
                'robots' => 'index, follow',
                'google_analytics_id' => null,
                'google_tag_manager_id' => null,
                'meta_pixel_id' => null,
                'schema_organization' => [
                    'name' => 'IK Saudi For Industries',
                    'url' => config('app.url'),
                ],
            ],
            'advanced' => [
                'maintenance_enabled' => false,
                'maintenance_message' => [
                    'ar' => 'الموقع قيد الصيانة. نعود قريباً.',
                    'en' => 'We are performing scheduled maintenance. Please check back soon.',
                ],
                'not_found_message' => [
                    'ar' => 'الصفحة غير موجودة.',
                    'en' => 'The page you requested could not be found.',
                ],
                'smtp_host' => null,
                'smtp_port' => null,
                'smtp_encryption' => null,
                'cache_enabled' => true,
            ],
        ]);
    }
}
