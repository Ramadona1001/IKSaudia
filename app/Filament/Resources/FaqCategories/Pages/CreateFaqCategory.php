<?php

namespace App\Filament\Resources\FaqCategories\Pages;

use App\Filament\Resources\Concerns\CreateTranslatableRecord;
use App\Filament\Resources\FaqCategories\FaqCategoryResource;
use App\Models\FaqCategoryTranslation;

class CreateFaqCategory extends CreateTranslatableRecord
{
    protected static string $resource = FaqCategoryResource::class;

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
