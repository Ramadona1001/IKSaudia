<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * Seeds Privacy Policy and Terms of Use CMS pages.
 *
 * Run: php artisan db:seed --class=LegalPagesSeeder
 */
class LegalPagesSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            PrivacyPolicyPageSeeder::class,
            TermsOfUsePageSeeder::class,
        ]);
    }
}
