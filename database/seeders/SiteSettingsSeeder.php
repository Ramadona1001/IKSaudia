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
                'quick_links' => [
                    ['label_en' => 'About Us', 'label_ar' => 'من نحن', 'url' => 'route:about', 'is_visible' => true, 'sort_order' => 0],
                    ['label_en' => 'Services', 'label_ar' => 'الخدمات', 'url' => 'route:services.index', 'is_visible' => true, 'sort_order' => 1],
                    ['label_en' => 'Industries', 'label_ar' => 'القطاعات', 'url' => 'route:industries.index', 'is_visible' => true, 'sort_order' => 2],
                    ['label_en' => 'Products', 'label_ar' => 'المنتجات', 'url' => 'route:products.index', 'is_visible' => true, 'sort_order' => 3],
                    ['label_en' => 'Clients', 'label_ar' => 'العملاء', 'url' => 'route:clients', 'is_visible' => true, 'sort_order' => 4],
                    ['label_en' => 'Partners', 'label_ar' => 'الشركاء', 'url' => 'route:partners', 'is_visible' => true, 'sort_order' => 5],
                    ['label_en' => 'FAQ', 'label_ar' => 'الأسئلة الشائعة', 'url' => 'route:faq', 'is_visible' => true, 'sort_order' => 6],
                    ['label_en' => 'Contact', 'label_ar' => 'اتصل بنا', 'url' => 'route:contact', 'is_visible' => true, 'sort_order' => 7],
                ],
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
                'form_eyebrow' => ['ar' => 'أرسل رسالة', 'en' => 'Send a Message'],
                'form_title' => ['ar' => 'طلب عرض سعر أو', 'en' => 'Request a Quote or'],
                'form_title_accent' => ['ar' => 'ناقش مشروعك', 'en' => 'Discuss Your Project'],
                'form_intro' => [
                    'ar' => 'املأ النموذج وسيتواصل فريقنا معك خلال 24 ساعة.',
                    'en' => 'Fill in the form and our team will get back to you within 24 hours.',
                ],
                'form_fields' => [
                    ['key' => 'name', 'type' => 'text', 'label_en' => 'Full Name', 'label_ar' => 'الاسم الكامل', 'placeholder_en' => 'Eng. Mohammed Al-…', 'placeholder_ar' => 'م. محمد ال...', 'is_required' => true, 'width' => 'half', 'is_visible' => true, 'sort_order' => 0, 'options' => []],
                    ['key' => 'company', 'type' => 'text', 'label_en' => 'Company Name', 'label_ar' => 'اسم الشركة', 'placeholder_en' => 'Saudi Aramco, SABIC…', 'placeholder_ar' => 'أرامكو السعودية، سابك...', 'is_required' => false, 'width' => 'half', 'is_visible' => true, 'sort_order' => 1, 'options' => []],
                    ['key' => 'email', 'type' => 'email', 'label_en' => 'Email Address', 'label_ar' => 'البريد الإلكتروني', 'placeholder_en' => 'you@company.com', 'placeholder_ar' => 'you@company.com', 'is_required' => true, 'width' => 'half', 'is_visible' => true, 'sort_order' => 2, 'options' => []],
                    ['key' => 'phone', 'type' => 'tel', 'label_en' => 'Phone Number', 'label_ar' => 'رقم الهاتف', 'placeholder_en' => '+966 5X XXX XXXX', 'placeholder_ar' => '+966 5X XXX XXXX', 'is_required' => false, 'width' => 'half', 'is_visible' => true, 'sort_order' => 3, 'options' => []],
                    ['key' => 'subject', 'type' => 'text', 'label_en' => 'Subject', 'label_ar' => 'الموضوع', 'placeholder_en' => 'How can we help?', 'placeholder_ar' => 'كيف يمكننا المساعدة؟', 'is_required' => true, 'width' => 'full', 'is_visible' => true, 'sort_order' => 4, 'options' => []],
                    ['key' => 'message', 'type' => 'textarea', 'label_en' => 'Project Description', 'label_ar' => 'وصف المشروع', 'placeholder_en' => 'Describe your project requirements, specifications, timeline, and any relevant details…', 'placeholder_ar' => 'صف متطلبات مشروعك والمواصفات والجدول الزمني وأي تفاصيل ذات صلة...', 'is_required' => true, 'width' => 'full', 'is_visible' => true, 'sort_order' => 5, 'options' => []],
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
