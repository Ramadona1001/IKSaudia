<?php

namespace App\Filament\Resources\NewsPosts\Pages;

use App\Filament\Resources\Concerns\EditTranslatableRecord;
use App\Filament\Resources\NewsPosts\NewsPostResource;
use App\Models\NewsPostTranslation;
use Filament\Actions\DeleteAction;

class EditNewsPost extends EditTranslatableRecord
{
    protected static string $resource = NewsPostResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }

    protected function translationModel(): string
    {
        return NewsPostTranslation::class;
    }

    protected function translationForeignKey(): string
    {
        return 'news_post_id';
    }

    protected function translationFields(): array
    {
        return ['title', 'slug', 'excerpt', 'body'];
    }
}
