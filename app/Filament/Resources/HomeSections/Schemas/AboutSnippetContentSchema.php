<?php

namespace App\Filament\Resources\HomeSections\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Get;

final class AboutSnippetContentSchema
{
    public static function section(): Section
    {
        return Section::make(__('cms.sections.about_content'))
            ->description(__('cms.sections.about_content_help'))
            ->icon('heroicon-o-document-text')
            ->columnSpanFull()
            ->visible(fn (Get $get): bool => $get('type') === 'about_snippet')
            ->schema([
                Tabs::make('aboutContentLocales')
                    ->tabs([
                        Tab::make(__('cms.tabs.arabic'))
                            ->schema(self::localeSchema('ar')),
                        Tab::make(__('cms.tabs.english'))
                            ->schema(self::localeSchema('en')),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    /** @return list<\Filament\Forms\Components\Component> */
    private static function localeSchema(string $locale): array
    {
        return [
            TextInput::make("translations.{$locale}.title")
                ->label(__('cms.fields.title'))
                ->maxLength(255),
            TextInput::make("translations.{$locale}.subtitle")
                ->label(__('cms.fields.subtitle'))
                ->maxLength(255),
            Textarea::make("translations.{$locale}.content")
                ->label(__('cms.fields.content'))
                ->rows(6)
                ->columnSpanFull()
                ->helperText(__('cms.fields.home_section_content_help')),
            TextInput::make("translations.{$locale}.cta_label")
                ->label(__('cms.fields.cta_label')),
            TextInput::make("translations.{$locale}.cta_url")
                ->label(__('cms.fields.cta_url'))
                ->helperText(__('cms.fields.about_cta_url_help')),
        ];
    }
}
