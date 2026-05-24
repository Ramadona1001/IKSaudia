<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Services\ClientPartnerLegacyImporter;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    public function run(): void
    {
        Client::query()->each(fn (Client $client) => $client->forceDelete());

        $this->command?->info('Importing clients from iksaudi.com...');

        $count = app(ClientPartnerLegacyImporter::class)->importClients();

        $this->command?->info("Imported {$count} clients with logos from legacy site.");
    }
}
