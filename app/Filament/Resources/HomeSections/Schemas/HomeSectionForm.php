<?php

namespace App\Filament\Resources\HomeSections\Schemas;

use App\Filament\Support\FormSchemas;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class HomeSectionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('Section')
                    ->columnSpanFull()
                    ->columns(2)
                    ->schema([
                        TextInput::make('key')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->helperText('e.g. hero, about_snippet, services_grid'),
                        Select::make('type')
                            ->options([
                                'hero' => 'Hero',
                                'about_snippet' => 'About snippet',
                                'stats' => 'Stats',
                                'services_grid' => 'Services grid',
                                'projects_carousel' => 'Projects carousel',
                                'clients_logos' => 'Clients logos',
                                'certifications' => 'Certifications',
                                'cta' => 'Call to action',
                            ])
                            ->required(),
                        Toggle::make('is_active')
                            ->default(true),
                        TextInput::make('sort_order')
                            ->numeric()
                            ->default(0),
                        ...HeroSlidesSchema::settingsFields(),
                        FormSchemas::featuredImageUpload('home-sections')
                            ->label(__('cms.fields.featured_image'))
                            ->helperText(__('cms.fields.home_section_image_help'))
                            ->visible(fn (Get $get): bool => $get('type') !== 'hero')
                            ->columnSpanFull(),
                        KeyValue::make('settings')
                            ->label('Layout settings')
                            ->visible(fn (Get $get): bool => ! in_array($get('type'), ['hero', 'about_snippet'], true))
                            ->columnSpanFull(),
                    ]),
                HeroSlidesSchema::section(),
                AboutSnippetSettingsSchema::section(),
                Tabs::make('Translations')
                    ->columnSpanFull()
                    ->visible(fn (Get $get): bool => $get('type') !== 'hero')
                    ->tabs([
                        self::translationTab('ar', 'العربية'),
                        self::translationTab('en', 'English'),
                    ]),
            ]);
    }

    protected static function translationTab(string $locale, string $label): Tab
    {
        return Tab::make($label)
            ->columns(2)
            ->schema([
                TextInput::make("translations.{$locale}.title")
                    ->label('Title')
                    ->maxLength(255),
                TextInput::make("translations.{$locale}.subtitle")
                    ->label('Subtitle')
                    ->maxLength(255),
                Textarea::make("translations.{$locale}.content")
                    ->label(__('cms.fields.content'))
                    ->rows(6)
                    ->columnSpanFull()
                    ->helperText(__('cms.fields.home_section_content_help')),
                TextInput::make("translations.{$locale}.cta_label")
                    ->label('CTA label'),
                TextInput::make("translations.{$locale}.cta_url")
                    ->label('CTA URL')
                    ->url(),
            ]);
    }
}
