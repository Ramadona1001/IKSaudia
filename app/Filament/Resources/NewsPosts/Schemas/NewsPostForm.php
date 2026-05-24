<?php

namespace App\Filament\Resources\NewsPosts\Schemas;

use App\Filament\Support\FormSchemas;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class NewsPostForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Post details')->columns(2)->schema([
                FormSchemas::featuredImageUpload('news-posts'),
                Select::make('author_id')
                    ->label('Author')
                    ->relationship('author', 'name')
                    ->searchable()
                    ->preload(),
                Toggle::make('is_featured')->label('Featured'),
            ]),
            FormSchemas::publishSection(),
            FormSchemas::translationTabs(fn (string $locale) => FormSchemas::newsFields($locale, true)),
            ...FormSchemas::seoSections(),
        ]);
    }
}
