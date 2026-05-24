<?php

namespace Database\Seeders;

use App\Models\Redirect;
use Illuminate\Database\Seeder;

class RedirectSeeder extends Seeder
{
    /**
     * Legacy URL map from iksaudi.com (old CMS) → new locale-prefixed routes.
     */
    public function run(): void
    {
        $redirects = [
            // Root & common entry points
            ['/index.html', '/ar'],
            ['/index.php', '/ar'],
            ['/home.html', '/ar'],

            // About (known legacy URL from live site)
            ['/About_Us_-223.html', '/ar/about-us'],
            ['/about_us.html', '/ar/about-us'],
            ['/About-Us.html', '/en/about-us'],
            ['/about.html', '/ar/about-us'],

            // Products
            ['/Our_Products-258.html', '/products'],
            ['/our-products.html', '/products'],

            // Services
            ['/services.html', '/ar/services'],
            ['/Services.html', '/en/services'],
            ['/our-services.html', '/ar/services'],

            // Industries
            ['/industries.html', '/ar/industries'],
            ['/Industries.html', '/en/industries'],

            // Projects
            ['/projects.html', '/ar/projects'],
            ['/Projects.html', '/en/projects'],

            // Certifications
            ['/certifications.html', '/ar/certifications'],
            ['/Certifications.html', '/en/certifications'],

            // Clients & partners
            ['/clients.html', '/ar/clients'],
            ['/partners.html', '/ar/partners'],

            // News
            ['/news.html', '/ar/news'],
            ['/News.html', '/en/news'],

            // Careers
            ['/careers.html', '/ar/careers'],
            ['/Careers.html', '/en/careers'],

            // Contact
            ['/contact.html', '/ar/contact'],
            ['/Contact.html', '/en/contact'],
            ['/contact-us.html', '/ar/contact'],
            ['/Contact_Us.html', '/ar/contact'],
        ];

        foreach ($redirects as [$from, $to]) {
            Redirect::query()->updateOrCreate(
                ['from_path' => $from],
                ['to_path' => $to, 'status_code' => 301, 'is_active' => true],
            );
        }
    }
}
