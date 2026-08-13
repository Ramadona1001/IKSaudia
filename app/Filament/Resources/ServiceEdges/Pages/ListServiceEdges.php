<?php

namespace App\Filament\Resources\ServiceEdges\Pages;

use App\Filament\Resources\ServiceEdges\ServiceEdgeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListServiceEdges extends ListRecords
{
    protected static string $resource = ServiceEdgeResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
