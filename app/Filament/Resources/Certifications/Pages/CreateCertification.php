<?php

namespace App\Filament\Resources\Certifications\Pages;

use App\Filament\Resources\Certifications\CertificationResource;
use App\Filament\Resources\Concerns\CreateTranslatableRecord;
use App\Models\CertificationTranslation;

class CreateCertification extends CreateTranslatableRecord
{
    protected static string $resource = CertificationResource::class;

    protected function translationModel(): string
    {
        return CertificationTranslation::class;
    }

    protected function translationForeignKey(): string
    {
        return 'certification_id';
    }

    protected function translationFields(): array
    {
        return ['title', 'slug', 'description'];
    }
}
