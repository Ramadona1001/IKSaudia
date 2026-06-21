<?php

namespace Database\Seeders;

use Database\Seeders\Concerns\SeedsCmsPage;
use Illuminate\Database\Seeder;

/**
 * Seeds the Terms of Use CMS page (pages.key = terms-of-use).
 *
 * URLs: /ar/terms-of-use · /en/terms-of-use
 *
 * Run: php artisan db:seed --class=TermsOfUsePageSeeder
 */
class TermsOfUsePageSeeder extends Seeder
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
            'key' => 'terms-of-use',
            'slug' => 'terms-of-use',
            'sort_order' => 11,
            'translations' => [
                'ar' => [
                    'title' => 'شروط الاستخدام',
                    'excerpt' => 'الشروط والأحكام التي تحكم استخدامك لموقع الشركة السعودية للصناعات (IKS).',
                    'body' => implode('', [
                        '<p>باستخدامك لموقع الشركة السعودية للصناعات (IK Saudi For Industries — IKS)، فإنك توافق على شروط الاستخدام هذه. إذا لم توافق على هذه الشروط، يرجى عدم استخدام الموقع.</p>',
                        '<h2>استخدام الموقع</h2>',
                        '<p>يُقدَّم الموقع لأغراض إعلامية وللتواصل معنا بشأن خدماتنا ومنتجاتنا الصناعية. يجب ألا تستخدم الموقع لأي غرض غير قانوني أو ضار أو ينتهك حقوق الآخرين.</p>',
                        '<h2>الملكية الفكرية</h2>',
                        '<p>جميع المحتويات والعلامات التجارية والمواد المنشورة على الموقع — بما في ذلك النصوص والصور والشعارات — مملوكة للشركة أو مرخصة لها ومحمية بموجب القوانين المعمول بها.</p>',
                        '<h2>دقة المعلومات</h2>',
                        '<p>نسعى لضمان دقة المعلومات المنشورة عن منتجاتنا وخدماتنا، لكننا لا نضمن اكتمالها أو ملاءمتها لجميع الأغراض التقنية أو التجارية. قد يتم تحديث المحتوى دون إشعار مسبق.</p>',
                        '<h2>العروض والاستفسارات</h2>',
                        '<p>المعلومات على الموقع لا تشكل عرضاً ملزماً. العروض الفنية والتجارية النهائية تُقدَّم كتابياً بعد مراجعة متطلبات المشروع.</p>',
                        '<h2>روابط خارجية</h2>',
                        '<p>قد يحتوي الموقع على روابط لمواقع خارجية. نحن غير مسؤولين عن محتوى أو ممارسات الخصوصية لتلك المواقع.</p>',
                        '<h2>تحديد المسؤولية</h2>',
                        '<p>لا تتحمل الشركة مسؤولية أي أضرار مباشرة أو غير مباشرة ناتجة عن استخدام الموقع أو الاعتماد على محتواه، إلى الحد الذي يسمح به القانون المعمول به.</p>',
                        '<h2>القانون الحاكم</h2>',
                        '<p>تخضع هذه الشروط لأنظمة المملكة العربية السعودية. للاستفسارات، يرجى التواصل معنا عبر صفحة التواصل على الموقع.</p>',
                    ]),
                ],
                'en' => [
                    'title' => 'Terms of Use',
                    'excerpt' => 'The terms and conditions governing your use of the IK Saudi For Industries (IKS) website.',
                    'body' => implode('', [
                        '<p>By using the IK Saudi For Industries (IKS) website, you agree to these Terms of Use. If you do not agree, please do not use the site.</p>',
                        '<h2>Use of the website</h2>',
                        '<p>The website is provided for informational purposes and to contact us about our industrial products and services. You must not use the site for any unlawful, harmful, or rights-infringing purpose.</p>',
                        '<h2>Intellectual property</h2>',
                        '<p>All content, trademarks, and materials published on the site — including text, images, and logos — are owned by or licensed to the company and protected under applicable laws.</p>',
                        '<h2>Accuracy of information</h2>',
                        '<p>We strive to keep published information about our products and services accurate but do not guarantee completeness or suitability for all technical or commercial purposes. Content may be updated without prior notice.</p>',
                        '<h2>Quotations and enquiries</h2>',
                        '<p>Information on the website does not constitute a binding offer. Final technical and commercial proposals are provided in writing after project requirements are reviewed.</p>',
                        '<h2>External links</h2>',
                        '<p>The site may contain links to external websites. We are not responsible for the content or privacy practices of those sites.</p>',
                        '<h2>Limitation of liability</h2>',
                        '<p>The company shall not be liable for any direct or indirect damages arising from use of the site or reliance on its content, to the extent permitted by applicable law.</p>',
                        '<h2>Governing law</h2>',
                        '<p>These terms are governed by the laws of the Kingdom of Saudi Arabia. For questions, please contact us via the contact page on this website.</p>',
                    ]),
                ],
            ],
            'seo' => [
                'ar' => [
                    'meta_title' => 'شروط الاستخدام | الشركة السعودية للصناعات',
                    'meta_description' => 'شروط استخدام موقع الشركة السعودية للصناعات (IKS).',
                ],
                'en' => [
                    'meta_title' => 'Terms of Use | IK Saudi For Industries',
                    'meta_description' => 'Terms of use for the IK Saudi For Industries (IKS) website.',
                ],
            ],
        ];
    }
}
