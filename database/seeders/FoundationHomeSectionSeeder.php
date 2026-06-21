<?php

namespace Database\Seeders;

use App\Models\HomeSection;
use App\Support\FoundationSection;
use Illuminate\Database\Seeder;

/**
 * Seeds Mission, Vision & Values (home_sections.key = foundation).
 *
 * Edit in admin: /ik-admin/home-sections → “foundation” section.
 *
 * Run: php artisan db:seed --class=FoundationHomeSectionSeeder
 */
class FoundationHomeSectionSeeder extends Seeder
{
    public function run(): void
    {
        HomeSection::query()->updateOrCreate(
            ['key' => 'foundation'],
            [
                'type' => 'foundation',
                'sort_order' => 3,
                'is_active' => true,
                'settings' => FoundationSection::defaultSettings(),
            ],
        );
    }
}
