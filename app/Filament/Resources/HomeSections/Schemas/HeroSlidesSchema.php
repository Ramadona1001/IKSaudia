<?php

namespace App\Filament\Resources\HomeSections\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Get;

final class HeroSlidesSchema
{
    public static function section(): Section
    {
        return Section::make('Hero slider')
            ->description('Background images with headline, text, and buttons per slide.')
            ->columnSpanFull()
            ->visible(fn (Get $get): bool => $get('type') === 'hero')
            ->schema([
                self::slidesRepeater(),
            ]);
    }

    public static function settingsFields(): array
    {
        return [
            Toggle::make('settings.autoplay')
                ->label('Autoplay')
                ->default(true)
                ->visible(fn (Get $get): bool => $get('type') === 'hero'),
            TextInput::make('settings.interval_ms')
                ->label('Autoplay interval (ms)')
                ->numeric()
                ->default(6000)
                ->minValue(3000)
                ->maxValue(30000)
                ->visible(fn (Get $get): bool => $get('type') === 'hero'),
        ];
    }

    protected static function slidesRepeater(): Repeater
    {
        return Repeater::make('slides')
            ->label('Slides')
            ->addActionLabel('Add slide')
            ->reorderable()
            ->orderColumn('sort_order')
            ->collapsible()
            ->itemLabel(fn (array $state): ?string => $state['translations']['ar']['title']
                ?? $state['translations']['en']['title']
                ?? __('Slide'))
            ->schema([
                FileUpload::make('image')
                    ->label('Background image')
                    ->image()
                    ->disk('public')
                    ->directory('hero-slides')
                    ->visibility('public')
                    ->maxSize(config('security.uploads.max_image_kb', 5120))
                    ->acceptedFileTypes(config('security.uploads.allowed_mimes', ['image/jpeg', 'image/png', 'image/webp']))
                    ->imageEditor()
                    ->imageEditorAspectRatios(['16:9', '21:9', null])
                    ->required()
                    ->columnSpanFull(),
                Toggle::make('is_active')
                    ->label('Active')
                    ->default(true),
                Tabs::make('Slide content')
                    ->tabs([
                        self::slideTab('ar', 'العربية'),
                        self::slideTab('en', 'English'),
                    ])
                    ->columnSpanFull(),
            ])
            ->columnSpanFull();
    }

    protected static function slideTab(string $locale, string $label): Tab
    {
        return Tab::make($label)
            ->schema([
                TextInput::make("translations.{$locale}.title")
                    ->label('Headline')
                    ->required($locale === 'ar')
                    ->maxLength(255),
                Textarea::make("translations.{$locale}.description")
                    ->label('Subtitle')
                    ->rows(3)
                    ->columnSpanFull(),
                TextInput::make("translations.{$locale}.button_text")
                    ->label('Primary button label')
                    ->maxLength(100),
                TextInput::make("translations.{$locale}.button_url")
                    ->label('Primary button URL')
                    ->maxLength(500)
                    ->placeholder('/en/contact or https://…'),
                TextInput::make("translations.{$locale}.secondary_button_text")
                    ->label('Secondary button label')
                    ->maxLength(100),
                TextInput::make("translations.{$locale}.secondary_button_url")
                    ->label('Secondary button URL')
                    ->maxLength(500),
            ]);
    }
}
