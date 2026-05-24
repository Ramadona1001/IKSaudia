<?php

namespace App\Filament\Resources\CareerApplications\Pages;

use App\Filament\Resources\CareerApplications\CareerApplicationResource;
use Filament\Resources\Pages\ListRecords;

class ListCareerApplications extends ListRecords
{
    protected static string $resource = CareerApplicationResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
