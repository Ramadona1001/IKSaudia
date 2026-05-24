<?php

namespace App\Filament\Resources\Certifications\Schemas;

use App\Filament\Support\FormSchemas;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CertificationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Certificate details')->columns(2)->schema([
                FormSchemas::featuredImageUpload('certifications'),
                TextInput::make('issuer')->maxLength(255),
                TextInput::make('certificate_number')->label('Certificate number')->maxLength(255),
                DatePicker::make('issued_at')->label('Issued at'),
                DatePicker::make('expires_at')->label('Expires at'),
                TextInput::make('document_path')->label('Document path')->maxLength(255)->columnSpanFull(),
                Toggle::make('is_featured')->label('Featured'),
            ]),
            FormSchemas::publishSection(),
            FormSchemas::translationTabs(fn (string $locale) => FormSchemas::certificationFields($locale, true)),
            ...FormSchemas::seoSections(),
        ]);
    }
}
