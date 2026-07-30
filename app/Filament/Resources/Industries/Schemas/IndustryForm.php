<?php

namespace App\Filament\Resources\Industries\Schemas;

use App\Filament\Support\FormSchemas;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class IndustryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Settings')->columns(2)->schema([
                FormSchemas::featuredImageUpload('industries'),
                FormSchemas::bootstrapIconSelect('icon', 'bi-grid-3x3-gap-fill'),
                Toggle::make('is_featured')->label('Featured'),
            ]),
            FormSchemas::publishSection(),
            FormSchemas::translationTabs(fn (string $locale) => FormSchemas::contentFields($locale, true)),
            ...FormSchemas::seoSections(),
        ]);
    }
}
