<?php

namespace App\Filament\Resources\ProductSpecDownloadRequests\Pages;

use App\Filament\Resources\ProductSpecDownloadRequests\ProductSpecDownloadRequestResource;
use Filament\Resources\Pages\ListRecords;

class ListProductSpecDownloadRequests extends ListRecords
{
    protected static string $resource = ProductSpecDownloadRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
