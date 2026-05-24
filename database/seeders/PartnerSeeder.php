<?php

namespace Database\Seeders;

use App\Models\Partner;
use App\Services\ClientPartnerLegacyImporter;
use Illuminate\Database\Seeder;

class PartnerSeeder extends Seeder
{
    public function run(): void
    {
        Partner::query()->each(fn (Partner $partner) => $partner->forceDelete());

        $this->command?->info('Importing partners from iksaudi.com...');

        $count = app(ClientPartnerLegacyImporter::class)->importPartners();

        $this->command?->info("Imported {$count} partners with logos from legacy site.");
    }
}
