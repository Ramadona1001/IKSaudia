<?php

namespace App\Filament\Resources\Careers\Pages;

use App\Filament\Resources\Careers\CareerResource;
use App\Filament\Resources\Concerns\EditTranslatableRecord;
use App\Models\CareerTranslation;
use Filament\Actions\DeleteAction;

class EditCareer extends EditTranslatableRecord
{
    protected static string $resource = CareerResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }

    protected function translationModel(): string
    {
        return CareerTranslation::class;
    }

    protected function translationForeignKey(): string
    {
        return 'career_id';
    }

    protected function translationFields(): array
    {
        return ['title', 'slug', 'summary', 'requirements', 'responsibilities', 'benefits'];
    }
}
