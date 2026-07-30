<?php

namespace Database\Seeders;

use App\Services\SettingsService;
use App\Support\HomeSectionHeading;
use Illuminate\Database\Seeder;

class HomePageSectionHeadingsSeeder extends Seeder
{
    public function run(): void
    {
        $payload = HomeSectionHeading::defaultSettingsPayload();

        app(SettingsService::class)->syncPartialFromForm([
            'homepage' => [
                'section_headings' => $payload,
            ],
        ]);

        $sectionCount = count($payload);

        $this->command?->info("Seeded {$sectionCount} homepage section headings (AR/EN) to website settings.");
    }
}
