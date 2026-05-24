<?php

namespace App\Filament\Resources\Certifications\Pages;

use App\Filament\Resources\Certifications\CertificationResource;
use App\Filament\Resources\Concerns\EditTranslatableRecord;
use App\Models\CertificationTranslation;
use Filament\Actions\DeleteAction;

class EditCertification extends EditTranslatableRecord
{
    protected static string $resource = CertificationResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }

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
