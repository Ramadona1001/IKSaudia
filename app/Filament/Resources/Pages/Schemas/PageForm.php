<?php

namespace App\Filament\Resources\Pages\Schemas;

use App\Filament\Support\FormSchemas;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class PageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('Page settings')
                    ->columns(2)
                    ->schema([
                        FormSchemas::featuredImageUpload('pages'),
                        TextInput::make('key')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->helperText('Locale-neutral identifier, e.g. about, privacy-policy'),
                        Select::make('template')
                            ->options([
                                'default' => 'Default',
                                'full-width' => 'Full width',
                                'landing' => 'Landing',
                            ])
                            ->default('default')
                            ->required(),
                        Toggle::make('is_published')
                            ->label('Published')
                            ->default(false),
                        DateTimePicker::make('published_at')
                            ->label('Publish at')
                            ->seconds(false),
                        TextInput::make('sort_order')
                            ->numeric()
                            ->default(0),
                    ]),
                Tabs::make('Translations')
                    ->tabs([
                        self::translationTab('ar', 'العربية'),
                        self::translationTab('en', 'English'),
                    ]),
                Section::make('SEO — Arabic')
                    ->collapsed()
                    ->schema(self::seoFields('ar')),
                Section::make('SEO — English')
                    ->collapsed()
                    ->schema(self::seoFields('en')),
            ]);
    }

    protected static function translationTab(string $locale, string $label): Tab
    {
        return Tab::make($label)
            ->schema([
                TextInput::make("translations.{$locale}.title")
                    ->label('Title')
                    ->required($locale === 'ar')
                    ->maxLength(255),
                TextInput::make("translations.{$locale}.slug")
                    ->label('URL slug')
                    ->required($locale === 'ar')
                    ->maxLength(255),
                Textarea::make("translations.{$locale}.excerpt")
                    ->label('Excerpt')
                    ->rows(3),
                RichEditor::make("translations.{$locale}.body")
                    ->label('Content')
                    ->columnSpanFull(),
            ]);
    }

    /** @return list<\Filament\Forms\Components\Field> */
    protected static function seoFields(string $locale): array
    {
        return [
            TextInput::make("seo.{$locale}.meta_title")
                ->label('Meta title')
                ->maxLength(70),
            Textarea::make("seo.{$locale}.meta_description")
                ->label('Meta description')
                ->rows(2)
                ->maxLength(160),
            TextInput::make("seo.{$locale}.og_image")
                ->label('OG image URL')
                ->url(),
        ];
    }
}
