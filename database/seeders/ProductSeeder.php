<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Services\ProductLegacyImporter;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        if (Product::query()->exists()) {
            $this->command?->warn('Products already seeded — skipping legacy import.');

            return;
        }

        $this->command?->info('Importing products from iksaudi.com (this may take a few minutes)...');

        $count = app(ProductLegacyImporter::class)->import();

        $this->command?->info("Imported {$count} products from legacy site.");
    }
}
