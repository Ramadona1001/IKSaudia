<?php

namespace Database\Seeders;

use App\Models\HomeSection;
use App\Models\HomeSectionTranslation;
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
        $settings = FoundationSection::defaultSettings();

        $section = HomeSection::query()->updateOrCreate(
            ['key' => 'foundation'],
            [
                'type' => 'foundation',
                'sort_order' => 3,
                'is_active' => true,
                'settings' => $settings,
            ],
        );

        foreach (['ar', 'en'] as $locale) {
            HomeSectionTranslation::query()->updateOrCreate(
                ['home_section_id' => $section->id, 'locale' => $locale],
                [
                    'content' => FoundationSection::encodePayload(
                        FoundationSection::localePayloadFromSettings($settings, $locale),
                    ),
                ],
            );
        }
    }
}
