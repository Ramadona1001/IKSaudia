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
        $this->seedAboutPage();
        $this->seedLegalPage(
            key: 'privacy-policy',
            sortOrder: 10,
            slug: 'privacy-policy',
            translations: [
                'ar' => [
                    'title' => 'سياسة الخصوصية',
                    'excerpt' => 'كيف نجمع بياناتك ونستخدمها ونحميها عند استخدام موقعنا.',
                    'body' => '<p>تحترم الشركة السعودية للصناعات (IK Saudi For Industries) خصوصيتك وتلتزم بحماية المعلومات الشخصية التي تشاركها معنا عبر موقعنا الإلكتروني أو نماذج التواصل.</p><h2>المعلومات التي نجمعها</h2><p>قد نجمع معلومات مثل الاسم والبريد الإلكتروني ورقم الهاتف واسم الشركة والرسالة التي ترسلها عبر نموذج التواصل.</p><h2>كيف نستخدم المعلومات</h2><p>نستخدم هذه المعلومات للرد على استفساراتك وتقديم الخدمات المطلوبة وتحسين تجربة المستخدم والتواصل معك بشأن مشاريعك.</p><h2>مشاركة البيانات</h2><p>لا نبيع بياناتك الشخصية. قد نشارك المعلومات مع مزودي خدمات موثوقين فقط عند الضرورة لتشغيل موقعنا أو تقديم خدماتنا، ومع الجهات المختصة عند وجود التزام قانوني.</p><h2>الاحتفاظ بالبيانات</h2><p>نحتفظ بالبيانات للمدة اللازمة لتحقيق الأغراض المذكورة أو وفقاً للمتطلبات القانونية المعمول بها في المملكة العربية السعودية.</p><h2>حقوقك</h2><p>يمكنك طلب الوصول إلى بياناتك أو تصحيحها أو حذفها بالتواصل معنا عبر البريد الإلكتروني المذكور في صفحة التواصل.</p><h2>التحديثات</h2><p>قد نقوم بتحديث هذه السياسة من وقت لآخر. يُعد استمرارك في استخدام الموقع موافقة على النسخة المحدّثة.</p>',
                ],
                'en' => [
                    'title' => 'Privacy Policy',
                    'excerpt' => 'How we collect, use, and protect your information when you use our website.',
                    'body' => '<p>IK Saudi For Industries respects your privacy and is committed to protecting personal information you share with us through our website or contact forms.</p><h2>Information we collect</h2><p>We may collect information such as your name, email address, phone number, company name, and the message you submit through our contact form.</p><h2>How we use information</h2><p>We use this information to respond to your enquiries, provide requested services, improve user experience, and communicate with you about your projects.</p><h2>Sharing of data</h2><p>We do not sell your personal data. We may share information with trusted service providers only when necessary to operate our website or deliver our services, and with authorities when legally required.</p><h2>Data retention</h2><p>We retain data for as long as needed to fulfil the purposes described above or as required by applicable laws in the Kingdom of Saudi Arabia.</p><h2>Your rights</h2><p>You may request access to, correction of, or deletion of your data by contacting us using the email address listed on our contact page.</p><h2>Updates</h2><p>We may update this policy from time to time. Continued use of the website constitutes acceptance of the updated version.</p>',
                ],
            ],
            seo: [
                'ar' => [
                    'meta_title' => 'سياسة الخصوصية | الشركة السعودية للصناعات',
                    'meta_description' => 'سياسة الخصوصية لموقع الشركة السعودية للصناعات.',
                ],
                'en' => [
                    'meta_title' => 'Privacy Policy | IK Saudi For Industries',
                    'meta_description' => 'Privacy policy for IK Saudi For Industries website.',
                ],
            ],
        );
        $this->seedLegalPage(
            key: 'terms-of-use',
            sortOrder: 11,
            slug: 'terms-of-use',
            translations: [
                'ar' => [
                    'title' => 'شروط الاستخدام',
                    'excerpt' => 'الشروط والأحكام التي تحكم استخدامك لموقعنا الإلكتروني.',
                    'body' => '<p>باستخدامك لموقع الشركة السعودية للصناعات (IK Saudi For Industries)، فإنك توافق على شروط الاستخدام هذه. إذا لم توافق على هذه الشروط، يرجى عدم استخدام الموقع.</p><h2>استخدام الموقع</h2><p>يُقدَّم الموقع لأغراض إعلامية وللتواصل معنا بشأن خدماتنا الصناعية. يجب ألا تستخدم الموقع لأي غرض غير قانوني أو ضار.</p><h2>الملكية الفكرية</h2><p>جميع المحتويات والعلامات والمواد المنشورة على الموقع مملوكة للشركة أو مرخصة لها ومحمية بموجب القوانين المعمول بها.</p><h2>دقة المعلومات</h2><p>نسعى لضمان دقة المعلومات المنشورة، لكننا لا نضمن اكتمالها أو ملاءمتها لجميع الأغراض. قد يتم تحديث المحتوى دون إشعار مسبق.</p><h2>روابط خارجية</h2><p>قد يحتوي الموقع على روابط لمواقع خارجية. نحن غير مسؤولين عن محتوى أو ممارسات تلك المواقع.</p><h2>تحديد المسؤولية</h2><p>لا تتحمل الشركة مسؤولية أي أضرار مباشرة أو غير مباشرة ناتجة عن استخدام الموقع أو الاعتماد على محتواه.</p><h2>القانون الحاكم</h2><p>تخضع هذه الشروط لأنظمة المملكة العربية السعودية. للاستفسارات، يرجى التواصل معنا عبر صفحة التواصل.</p>',
                ],
                'en' => [
                    'title' => 'Terms of Use',
                    'excerpt' => 'The terms and conditions governing your use of our website.',
                    'body' => '<p>By using the IK Saudi For Industries website, you agree to these Terms of Use. If you do not agree, please do not use the site.</p><h2>Use of the website</h2><p>The website is provided for informational purposes and to contact us about our industrial services. You must not use the site for any unlawful or harmful purpose.</p><h2>Intellectual property</h2><p>All content, trademarks, and materials published on the site are owned by or licensed to the company and protected under applicable laws.</p><h2>Accuracy of information</h2><p>We strive to keep published information accurate but do not guarantee completeness or suitability for all purposes. Content may be updated without prior notice.</p><h2>External links</h2><p>The site may contain links to external websites. We are not responsible for the content or practices of those sites.</p><h2>Limitation of liability</h2><p>The company shall not be liable for any direct or indirect damages arising from use of the site or reliance on its content.</p><h2>Governing law</h2><p>These terms are governed by the laws of the Kingdom of Saudi Arabia. For questions, please contact us via our contact page.</p>',
                ],
            ],
            seo: [
                'ar' => [
                    'meta_title' => 'شروط الاستخدام | الشركة السعودية للصناعات',
                    'meta_description' => 'شروط استخدام موقع الشركة السعودية للصناعات.',
                ],
                'en' => [
                    'meta_title' => 'Terms of Use | IK Saudi For Industries',
                    'meta_description' => 'Terms of use for IK Saudi For Industries website.',
                ],
            ],
        );
    }

    private function seedAboutPage(): void
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

    /**
     * @param  array<string, array{title: string, excerpt: string, body: string}>  $translations
     * @param  array<string, array{meta_title: string, meta_description: string}>  $seo
     */
    private function seedLegalPage(string $key, int $sortOrder, string $slug, array $translations, array $seo): void
    {
        $page = Page::query()->updateOrCreate(
            ['key' => $key],
            [
                'template' => 'default',
                'is_published' => true,
                'published_at' => now(),
                'sort_order' => $sortOrder,
            ],
        );

        foreach ($translations as $locale => $content) {
            PageTranslation::query()->updateOrCreate(
                ['page_id' => $page->id, 'locale' => $locale],
                [
                    'title' => $content['title'],
                    'slug' => $slug,
                    'excerpt' => $content['excerpt'],
                    'body' => $content['body'],
                ],
            );
        }

        foreach ($seo as $locale => $meta) {
            SeoMeta::query()->updateOrCreate(
                [
                    'seoable_type' => Page::class,
                    'seoable_id' => $page->id,
                    'locale' => $locale,
                ],
                $meta,
            );
        }
    }
}
