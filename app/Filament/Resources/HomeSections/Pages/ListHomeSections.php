<?php

namespace App\Filament\Resources\HomeSections\Pages;

use App\Filament\Resources\HomeSections\HomeSectionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListHomeSections extends ListRecords
{
    protected static string $resource = HomeSectionResource::class;

    protected function getTableQuery(): Builder
    {
        return parent::getTableQuery()->where('type', '!=', 'foundation');
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
