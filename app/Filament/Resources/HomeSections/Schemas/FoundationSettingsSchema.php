<?php

namespace App\Filament\Resources\HomeSections\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;

final class FoundationSettingsSchema
{
    public static function section(): Section
    {
        return Section::make(__('cms.sections.foundation'))
            ->description(__('cms.sections.foundation_help'))
            ->icon('heroicon-o-flag')
            ->columnSpanFull()
            ->visible(fn (Get $get): bool => $get('type') === 'foundation')
            ->schema([
                self::localeSection('ar', __('cms.tabs.arabic')),
                self::localeSection('en', __('cms.tabs.english')),
            ]);
    }

    private static function localeSection(string $locale, string $label): Section
    {
        return Section::make($label)
            ->collapsible()
            ->schema([
                Section::make(__('cms.sections.foundation_heading'))
                    ->columns(3)
                    ->schema([
                        TextInput::make("settings.heading.{$locale}.eyebrow")
                            ->label(__('cms.fields.section_eyebrow'))
                            ->maxLength(120),
                        TextInput::make("settings.heading.{$locale}.title")
                            ->label(__('cms.fields.section_title'))
                            ->maxLength(255),
                        TextInput::make("settings.heading.{$locale}.highlight")
                            ->label(__('cms.fields.section_highlight'))
                            ->maxLength(120),
                    ]),
                self::cardFields('vision', $locale, __('cms.fields.vision')),
                self::cardFields('mission', $locale, __('cms.fields.mission')),
                self::cardFields('values', $locale, __('cms.fields.values'), rows: 10),
            ]);
    }

    private static function cardFields(string $key, string $locale, string $label, int $rows = 4): Section
    {
        return Section::make($label)
            ->schema([
                TextInput::make("settings.{$key}.{$locale}.title")
                    ->label(__('cms.fields.card_title'))
                    ->maxLength(255),
                Textarea::make("settings.{$key}.{$locale}.description")
                    ->label(__('cms.fields.card_description'))
                    ->rows($rows)
                    ->columnSpanFull(),
            ]);
    }
}
