<?php

namespace App\Filament\Pages\Foundation;

use App\Filament\Resources\HomeSections\Schemas\FoundationSettingsSchema;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

final class FoundationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make(__('cms.sections.publishing'))
                ->columns(2)
                ->schema([
                    Toggle::make('is_active')
                        ->label(__('cms.fields.published'))
                        ->default(true)
                        ->helperText(__('cms.sections.foundation_visibility_help')),
                ]),
            FoundationSettingsSchema::contentSection(),
        ]);
    }
}
