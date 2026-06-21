<?php

namespace Database\Seeders;

use Database\Seeders\Concerns\SeedsCmsPage;
use Illuminate\Database\Seeder;

/**
 * Seeds the Privacy Policy CMS page (pages.key = privacy-policy).
 *
 * URLs: /ar/privacy-policy · /en/privacy-policy
 *
 * Run: php artisan db:seed --class=PrivacyPolicyPageSeeder
 */
class PrivacyPolicyPageSeeder extends Seeder
{
    use SeedsCmsPage;

    public function run(): void
    {
        $this->seedCmsPage(self::definition());
    }

    /**
     * @return array{
     *     key: string,
     *     slug: string,
     *     sort_order: int,
     *     translations: array<string, array{title: string, excerpt: string, body: string}>,
     *     seo: array<string, array{meta_title: string, meta_description: string}>
     * }
     */
    public static function definition(): array
    {
        return [
            'key' => 'privacy-policy',
            'slug' => 'privacy-policy',
            'sort_order' => 10,
            'translations' => [
                'ar' => [
                    'title' => 'سياسة الخصوصية',
                    'excerpt' => 'كيف تجمع الشركة السعودية للصناعات (IKS) بياناتك وتستخدمها وتحميها عند استخدام موقعنا.',
                    'body' => implode('', [
                        '<p>تحترم الشركة السعودية للصناعات (IK Saudi For Industries — IKS) خصوصيتك وتلتزم بحماية المعلومات الشخصية التي تشاركها معنا عبر موقعنا الإلكتروني أو نماذج التواصل أو مراسلاتنا التجارية.</p>',
                        '<h2>المعلومات التي نجمعها</h2>',
                        '<p>قد نجمع معلومات مثل الاسم، البريد الإلكتروني، رقم الهاتف، اسم الشركة، المسمى الوظيفي، والرسالة التي ترسلها عبر نموذج التواصل أو طلب عرض السعر.</p>',
                        '<h2>كيف نستخدم المعلومات</h2>',
                        '<p>نستخدم هذه المعلومات للرد على استفساراتك، وتقديم الخدمات والمنتجات المطلوبة، وإعداد العروض الفنية، وتحسين تجربة المستخدم، والتواصل معك بشأن مشاريعك الصناعية.</p>',
                        '<h2>مشاركة البيانات</h2>',
                        '<p>لا نبيع بياناتك الشخصية. قد نشارك المعلومات مع مزودي خدمات موثوقين فقط عند الضرورة لتشغيل موقعنا أو تقديم خدماتنا، ومع الجهات المختصة عند وجود التزام قانوني.</p>',
                        '<h2>الاحتفاظ بالبيانات</h2>',
                        '<p>نحتفظ بالبيانات للمدة اللازمة لتحقيق الأغراض المذكورة أو وفقاً للمتطلبات القانونية المعمول بها في المملكة العربية السعودية.</p>',
                        '<h2>الأمان</h2>',
                        '<p>نطبق إجراءات تقنية وتنظيمية معقولة لحماية بياناتك من الوصول غير المصرح به أو الفقدان أو التعديل.</p>',
                        '<h2>حقوقك</h2>',
                        '<p>يمكنك طلب الوصول إلى بياناتك أو تصحيحها أو حذفها بالتواصل معنا عبر البريد الإلكتروني أو صفحة التواصل على الموقع.</p>',
                        '<h2>التحديثات</h2>',
                        '<p>قد نقوم بتحديث هذه السياسة من وقت لآخر. يُعد استمرارك في استخدام الموقع موافقة على النسخة المحدّثة المنشورة على هذه الصفحة.</p>',
                    ]),
                ],
                'en' => [
                    'title' => 'Privacy Policy',
                    'excerpt' => 'How IK Saudi For Industries (IKS) collects, uses, and protects your information when you use our website.',
                    'body' => implode('', [
                        '<p>IK Saudi For Industries (IKS) respects your privacy and is committed to protecting personal information you share with us through our website, contact forms, or business correspondence.</p>',
                        '<h2>Information we collect</h2>',
                        '<p>We may collect information such as your name, email address, phone number, company name, job title, and the message you submit through our contact or quotation request forms.</p>',
                        '<h2>How we use information</h2>',
                        '<p>We use this information to respond to your enquiries, provide requested products and services, prepare technical proposals, improve user experience, and communicate with you about your industrial projects.</p>',
                        '<h2>Sharing of data</h2>',
                        '<p>We do not sell your personal data. We may share information with trusted service providers only when necessary to operate our website or deliver our services, and with authorities when legally required.</p>',
                        '<h2>Data retention</h2>',
                        '<p>We retain data for as long as needed to fulfil the purposes described above or as required by applicable laws in the Kingdom of Saudi Arabia.</p>',
                        '<h2>Security</h2>',
                        '<p>We apply reasonable technical and organisational measures to protect your data from unauthorised access, loss, or alteration.</p>',
                        '<h2>Your rights</h2>',
                        '<p>You may request access to, correction of, or deletion of your data by contacting us via the email address or contact page on this website.</p>',
                        '<h2>Updates</h2>',
                        '<p>We may update this policy from time to time. Continued use of the website constitutes acceptance of the updated version published on this page.</p>',
                    ]),
                ],
            ],
            'seo' => [
                'ar' => [
                    'meta_title' => 'سياسة الخصوصية | الشركة السعودية للصناعات',
                    'meta_description' => 'سياسة الخصوصية لموقع الشركة السعودية للصناعات (IKS) — كيف نجمع بياناتك ونحميها.',
                ],
                'en' => [
                    'meta_title' => 'Privacy Policy | IK Saudi For Industries',
                    'meta_description' => 'Privacy policy for the IK Saudi For Industries (IKS) website — how we collect and protect your data.',
                ],
            ],
        ];
    }
}
