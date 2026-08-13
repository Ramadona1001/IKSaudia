<?php

namespace App\Filament\Resources\ServiceEdges\Pages;

use App\Filament\Resources\Concerns\CreateTranslatableRecord;
use App\Filament\Resources\ServiceEdges\ServiceEdgeResource;
use App\Models\ServiceEdgeTranslation;

class CreateServiceEdge extends CreateTranslatableRecord
{
    protected static string $resource = ServiceEdgeResource::class;

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
