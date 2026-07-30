<?php

namespace App\Filament\Resources\FaqCategories\Schemas;

use App\Filament\Support\FormSchemas;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class FaqCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Category details')->columns(2)->schema([
                TextInput::make('key')
                    ->label('Key')
                    ->required()
                    ->maxLength(80)
                    ->unique(ignoreRecord: true)
                    ->helperText('Unique identifier, e.g. services, quality, projects'),
                Select::make('color')
                    ->label('Accent color')
                    ->options([
                        'gold' => 'Gold',
                        'blue' => 'Blue',
                    ])
                    ->default('gold')
                    ->native(false),
                TextInput::make('icon')
                    ->label('Bootstrap icon class')
                    ->default('bi-question-circle-fill')
                    ->maxLength(80)
                    ->columnSpanFull(),
            ]),
            Section::make('Publishing')->columns(2)->schema([
                Toggle::make('is_published')->label('Published')->default(true),
                TextInput::make('sort_order')->numeric()->default(0),
            ]),
            FormSchemas::translationTabs(fn (string $locale) => [
                TextInput::make("translations.{$locale}.title")
                    ->label('Category title')
                    ->required($locale === 'ar')
                    ->maxLength(255),
            ]),
        ]);
    }
}
