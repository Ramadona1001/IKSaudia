<?php

namespace App\Filament\Resources\Industries\Pages;

use App\Filament\Resources\Concerns\CreateTranslatableRecord;
use App\Filament\Resources\Industries\IndustryResource;
use App\Models\IndustryTranslation;

class CreateIndustry extends CreateTranslatableRecord
{
    protected static string $resource = IndustryResource::class;

    protected function translationModel(): string
    {
        return IndustryTranslation::class;
    }

    protected function translationForeignKey(): string
    {
        return 'industry_id';
    }

    protected function translationFields(): array
    {
        return ['title', 'slug', 'summary', 'body'];
    }
}
