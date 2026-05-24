<?php

namespace App\Filament\Resources\NewsPosts\Pages;

use App\Filament\Resources\Concerns\CreateTranslatableRecord;
use App\Filament\Resources\NewsPosts\NewsPostResource;
use App\Models\NewsPostTranslation;

class CreateNewsPost extends CreateTranslatableRecord
{
    protected static string $resource = NewsPostResource::class;

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
