<?php

namespace App\Filament\Resources\Services\Schemas;

use App\Filament\Support\FormSchemas;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class ServiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('Service settings')
                    ->columns(2)
                    ->schema([
                        FormSchemas::featuredImageUpload('services'),
                        TextInput::make('icon')
                            ->placeholder('heroicon name or CSS class'),
                        Toggle::make('is_published')
                            ->label(__('cms.fields.published'))
                            ->default(false)
                            ->helperText(__('cms.fields.service_publish_help')),
                        DateTimePicker::make('published_at')
                            ->seconds(false),
                        TextInput::make('sort_order')
                            ->numeric()
                            ->default(0),
                    ]),
                Tabs::make('Translations')
                    ->tabs([
                        self::tab('ar', 'العربية'),
                        self::tab('en', 'English'),
                    ]),
                Section::make('SEO — Arabic')->collapsed()->schema(self::seo('ar')),
                Section::make('SEO — English')->collapsed()->schema(self::seo('en')),
            ]);
    }

    protected static function tab(string $locale, string $label): Tab
    {
        return Tab::make($label)->schema([
            TextInput::make("translations.{$locale}.title")
                ->required($locale === 'ar')
                ->maxLength(255),
            TextInput::make("translations.{$locale}.slug")
                ->required($locale === 'ar')
                ->maxLength(255),
            Textarea::make("translations.{$locale}.summary")
                ->rows(3)
                ->columnSpanFull(),
            RichEditor::make("translations.{$locale}.body")
                ->columnSpanFull(),
            TextInput::make("translations.{$locale}.cta_label"),
            TextInput::make("translations.{$locale}.cta_url")->url(),
        ]);
    }

    /** @return list<\Filament\Forms\Components\Field> */
    protected static function seo(string $locale): array
    {
        return [
            TextInput::make("seo.{$locale}.meta_title")->maxLength(70),
            Textarea::make("seo.{$locale}.meta_description")->rows(2)->maxLength(160),
            TextInput::make("seo.{$locale}.og_image")->url(),
        ];
    }
}
