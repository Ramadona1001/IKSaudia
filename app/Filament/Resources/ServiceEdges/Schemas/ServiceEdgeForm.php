<?php

namespace App\Filament\Resources\ServiceEdges\Schemas;

use App\Filament\Support\FormSchemas;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ServiceEdgeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make(__('cms.sections.service_edge_details'))
                ->columns(2)
                ->schema([
                    FormSchemas::bootstrapIconSelect('icon', 'bi-patch-check-fill'),
                    Toggle::make('is_published')
                        ->label(__('cms.fields.published'))
                        ->default(true)
                        ->helperText(__('cms.fields.service_edge_publish_help')),
                    TextInput::make('sort_order')
                        ->numeric()
                        ->default(0),
                ]),
            FormSchemas::translationTabs(fn (string $locale) => [
                TextInput::make("translations.{$locale}.title")
                    ->label(__('cms.fields.card_title'))
                    ->required($locale === 'ar')
                    ->maxLength(255),
                Textarea::make("translations.{$locale}.description")
                    ->label(__('cms.fields.card_description'))
                    ->rows(3)
                    ->columnSpanFull(),
            ]),
        ]);
    }
}
