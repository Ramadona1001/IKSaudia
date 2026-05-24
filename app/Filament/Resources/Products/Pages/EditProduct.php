<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Concerns\EditTranslatableRecord;
use App\Filament\Resources\Products\ProductResource;
use App\Models\ProductTranslation;

class EditProduct extends EditTranslatableRecord
{
    protected static string $resource = ProductResource::class;

    protected function translationModel(): string
    {
        return ProductTranslation::class;
    }

    protected function translationForeignKey(): string
    {
        return 'product_id';
    }

    protected function translationFields(): array
    {
        return ['title', 'slug', 'summary', 'body'];
    }
}
