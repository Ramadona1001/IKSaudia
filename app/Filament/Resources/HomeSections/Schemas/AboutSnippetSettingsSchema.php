<?php

namespace App\Filament\Resources\HomeSections\Schemas;

use App\Support\AboutSectionStats;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;

final class AboutSnippetSettingsSchema
{
    public static function section(): Section
    {
        return Section::make(__('cms.sections.about_stats'))
            ->description(__('cms.sections.about_stats_help'))
            ->icon('heroicon-o-chart-bar')
            ->columnSpanFull()
            ->visible(fn (Get $get): bool => $get('type') === 'about_snippet')
            ->schema([
                self::localeSection('ar', __('cms.tabs.arabic')),
                self::localeSection('en', __('cms.tabs.english')),
            ]);
    }

    private static function localeSection(string $locale, string $label): Section
    {
        $yearsDefault = AboutSectionStats::defaultYearsBadgeForLocale($locale);

        return Section::make($label)
            ->collapsible()
            ->schema([
                Section::make(__('cms.sections.years_badge'))
                    ->columns(3)
                    ->schema([
                        TextInput::make("settings.years_badge.{$locale}.count")
                            ->label(__('cms.fields.years_badge_count'))
                            ->numeric()
                            ->minValue(0)
                            ->default($yearsDefault['count']),
                        TextInput::make("settings.years_badge.{$locale}.suffix")
                            ->label(__('cms.fields.stat_suffix'))
                            ->maxLength(5)
                            ->default($yearsDefault['suffix']),
                        TextInput::make("settings.years_badge.{$locale}.label")
                            ->label(__('cms.fields.stat_label'))
                            ->maxLength(120)
                            ->default($yearsDefault['label']),
                    ]),
                Repeater::make("settings.stats.{$locale}")
                    ->label(__('cms.fields.about_stat_items'))
                    ->schema([
                        TextInput::make('count')
                            ->label(__('cms.fields.stat_count'))
                            ->numeric()
                            ->required()
                            ->minValue(0),
                        TextInput::make('suffix')
                            ->label(__('cms.fields.stat_suffix'))
                            ->maxLength(5)
                            ->placeholder('+'),
                        Select::make('variant')
                            ->label(__('cms.fields.stat_variant'))
                            ->options([
                                'gold' => __('cms.fields.stat_variant_gold'),
                                'blue' => __('cms.fields.stat_variant_blue'),
                            ])
                            ->default('gold')
                            ->required(),
                        TextInput::make('label')
                            ->label(__('cms.fields.stat_label'))
                            ->maxLength(120)
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->default(AboutSectionStats::defaultStatsForLocale($locale))
                    ->minItems(4)
                    ->maxItems(4)
                    ->reorderable(false)
                    ->addable(false)
                    ->deletable(false)
                    ->columnSpanFull(),
            ]);
    }
}
