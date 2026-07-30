<?php

namespace App\Filament\Resources\Faqs\Pages;

use App\Filament\Resources\Concerns\CreateTranslatableRecord;
use App\Filament\Resources\Faqs\FaqResource;
use App\Models\FaqTranslation;

class CreateFaq extends CreateTranslatableRecord
{
    protected static string $resource = FaqResource::class;

    protected function hasSeo(): bool
    {
        return false;
    }

    protected function translationModel(): string
    {
        return FaqTranslation::class;
    }

    protected function translationForeignKey(): string
    {
        return 'faq_id';
    }

    protected function translationFields(): array
    {
        return ['question', 'answer'];
    }
}
