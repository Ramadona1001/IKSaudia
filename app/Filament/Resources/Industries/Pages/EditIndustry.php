<?php

namespace App\Filament\Resources\Industries\Pages;

use App\Filament\Resources\Concerns\EditTranslatableRecord;
use App\Filament\Resources\Industries\IndustryResource;
use App\Models\IndustryTranslation;
use Filament\Actions\DeleteAction;

class EditIndustry extends EditTranslatableRecord
{
    protected static string $resource = IndustryResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }

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
