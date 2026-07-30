<?php

namespace Database\Seeders;

use App\Services\SettingsService;
use App\Support\FooterLink;
use Illuminate\Database\Seeder;

class FooterLinkGroupsSeeder extends Seeder
{
    public function run(): void
    {
        $payload = FooterLink::linkGroupsPayload();

        app(SettingsService::class)->syncPartialFromForm([
            'footer' => $payload,
        ]);

        $this->command?->info(sprintf(
            'Seeded footer link groups: %d company, %d services, %d industries, %d legal.',
            count($payload['quick_links'] ?? []),
            count($payload['service_links'] ?? []),
            count($payload['industry_links'] ?? []),
            count($payload['legal_links'] ?? []),
        ));
    }
}
