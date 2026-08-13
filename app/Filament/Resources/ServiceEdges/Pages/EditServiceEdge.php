<?php

namespace App\Filament\Resources\ServiceEdges\Pages;

use App\Filament\Resources\Concerns\EditTranslatableRecord;
use App\Filament\Resources\ServiceEdges\ServiceEdgeResource;
use App\Models\ServiceEdgeTranslation;
use Filament\Actions\DeleteAction;

class EditServiceEdge extends EditTranslatableRecord
{
    protected static string $resource = ServiceEdgeResource::class;

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
        return ServiceEdgeTranslation::class;
    }

    protected function translationForeignKey(): string
    {
        return 'service_edge_id';
    }

    protected function translationFields(): array
    {
        return ['title', 'description'];
    }
}
