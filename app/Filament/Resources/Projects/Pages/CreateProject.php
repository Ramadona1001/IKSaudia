<?php

namespace App\Filament\Resources\Projects\Pages;

use App\Filament\Resources\Concerns\CreateTranslatableRecord;
use App\Filament\Resources\Projects\ProjectResource;
use App\Models\ProjectTranslation;

class CreateProject extends CreateTranslatableRecord
{
    protected static string $resource = ProjectResource::class;

    protected function translationModel(): string
    {
        return ProjectTranslation::class;
    }

    protected function translationForeignKey(): string
    {
        return 'project_id';
    }

    protected function translationFields(): array
    {
        return ['title', 'slug', 'summary', 'body'];
    }
}
