<?php

namespace App\Filament\Resources\Faqs\Pages;

use App\Filament\Resources\Concerns\EditTranslatableRecord;
use App\Filament\Resources\Faqs\FaqResource;
use App\Models\FaqTranslation;
use Filament\Actions\DeleteAction;

class EditFaq extends EditTranslatableRecord
{
    protected static string $resource = FaqResource::class;

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
