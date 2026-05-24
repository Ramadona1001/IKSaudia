<?php

namespace App\Filament\Resources\Careers\Pages;

use App\Filament\Resources\Careers\CareerResource;
use App\Filament\Resources\Concerns\CreateTranslatableRecord;
use App\Models\CareerTranslation;

class CreateCareer extends CreateTranslatableRecord
{
    protected static string $resource = CareerResource::class;

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
