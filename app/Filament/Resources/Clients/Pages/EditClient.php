<?php

namespace App\Filament\Resources\Clients\Pages;

use App\Filament\Resources\Clients\ClientResource;
use App\Filament\Resources\Concerns\EditTranslatableRecord;
use App\Models\ClientTranslation;
use Filament\Actions\DeleteAction;

class EditClient extends EditTranslatableRecord
{
    protected static string $resource = ClientResource::class;

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
        return ClientTranslation::class;
    }

    protected function translationForeignKey(): string
    {
        return 'client_id';
    }

    protected function translationFields(): array
    {
        return ['name', 'description'];
    }
}
