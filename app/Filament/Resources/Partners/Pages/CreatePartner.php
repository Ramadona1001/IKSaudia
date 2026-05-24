<?php

namespace App\Filament\Resources\Partners\Pages;

use App\Filament\Resources\Concerns\CreateTranslatableRecord;
use App\Filament\Resources\Partners\PartnerResource;
use App\Models\PartnerTranslation;

class CreatePartner extends CreateTranslatableRecord
{
    protected static string $resource = PartnerResource::class;

    protected function hasSeo(): bool
    {
        return false;
    }

    protected function translationModel(): string
    {
        return PartnerTranslation::class;
    }

    protected function translationForeignKey(): string
    {
        return 'partner_id';
    }

    protected function translationFields(): array
    {
        return ['name', 'description'];
    }
}
