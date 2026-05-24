<?php

namespace App\Filament\Resources\Clients\Pages;

use App\Filament\Resources\Clients\ClientResource;
use App\Filament\Resources\Concerns\CreateTranslatableRecord;
use App\Models\ClientTranslation;

class CreateClient extends CreateTranslatableRecord
{
    protected static string $resource = ClientResource::class;

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
