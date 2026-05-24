<?php

namespace App\Filament\Resources\Partners\Pages;

use App\Filament\Resources\Concerns\EditTranslatableRecord;
use App\Filament\Resources\Partners\PartnerResource;
use App\Models\PartnerTranslation;
use Filament\Actions\DeleteAction;

class EditPartner extends EditTranslatableRecord
{
    protected static string $resource = PartnerResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }

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
