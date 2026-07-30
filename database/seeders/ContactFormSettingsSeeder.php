<?php

namespace Database\Seeders;

use App\Services\SettingsService;
use App\Support\ContactForm;
use Illuminate\Database\Seeder;

class ContactFormSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $payload = ContactForm::settingsPayload();

        app(SettingsService::class)->syncPartialFromForm([
            'contact' => $payload,
        ]);

        $fieldCount = count($payload['form_fields'] ?? []);

        $this->command?->info("Seeded contact form heading and {$fieldCount} fields to website settings.");
    }
}
