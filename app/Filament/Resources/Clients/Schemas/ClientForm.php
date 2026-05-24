<?php

namespace App\Filament\Resources\Clients\Schemas;

use App\Filament\Support\FormSchemas;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ClientForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Client details')->columns(2)->schema([
                FormSchemas::featuredImageUpload('clients'),
                TextInput::make('website_url')->label('Website URL')->url()->maxLength(255),
                Toggle::make('is_featured')->label('Featured'),
            ]),
            Section::make('Publishing')->columns(2)->schema([
                Toggle::make('is_published')->label('Published')->default(false),
                TextInput::make('sort_order')->numeric()->default(0),
            ]),
            FormSchemas::translationTabs(fn (string $locale) => FormSchemas::simpleNameFields($locale)),
        ]);
    }
}
