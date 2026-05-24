<?php

namespace App\Filament\Resources\Projects\Pages;

use App\Filament\Resources\Concerns\EditTranslatableRecord;
use App\Filament\Resources\Projects\ProjectResource;
use App\Models\ProjectTranslation;
use Filament\Actions\DeleteAction;

class EditProject extends EditTranslatableRecord
{
    protected static string $resource = ProjectResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }

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
