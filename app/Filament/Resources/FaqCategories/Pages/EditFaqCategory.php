<?php

namespace App\Filament\Resources\FaqCategories\Pages;

use App\Filament\Resources\Concerns\EditTranslatableRecord;
use App\Filament\Resources\FaqCategories\FaqCategoryResource;
use App\Models\FaqCategoryTranslation;
use Filament\Actions\DeleteAction;

class EditFaqCategory extends EditTranslatableRecord
{
    protected static string $resource = FaqCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }

    protected function hasSeo(): bool
    {
        return false;
    }

    protected function translationModel(): string
    {
        return FaqCategoryTranslation::class;
    }

    protected function translationForeignKey(): string
    {
        return 'faq_category_id';
    }

    protected function translationFields(): array
    {
        return ['title'];
    }
}
