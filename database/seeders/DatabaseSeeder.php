<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            LocaleSeeder::class,
            AdminUserSeeder::class,
            RolePermissionSeeder::class,
            HomeContentSeeder::class,
            PageSeeder::class,
            ServiceSeeder::class,
            ProductSeeder::class,
            IndustrySeeder::class,
            CertificationSeeder::class,
            ProjectSeeder::class,
            ClientSeeder::class,
            PartnerSeeder::class,
            NewsPostSeeder::class,
            CareerSeeder::class,
            GallerySeeder::class,
            NavigationSeeder::class,
            RedirectSeeder::class,
            SiteSettingsSeeder::class,
        ]);
    }
}
