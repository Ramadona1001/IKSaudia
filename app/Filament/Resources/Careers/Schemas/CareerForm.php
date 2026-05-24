<?php

namespace App\Filament\Resources\Careers\Schemas;

use App\Filament\Support\FormSchemas;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CareerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Job details')->columns(2)->schema([
                FormSchemas::featuredImageUpload('careers'),
                TextInput::make('department')->maxLength(255),
                TextInput::make('location')->maxLength(255),
                Select::make('employment_type')
                    ->options([
                        'full_time' => 'Full time',
                        'part_time' => 'Part time',
                        'contract' => 'Contract',
                        'internship' => 'Internship',
                    ])
                    ->default('full_time')
                    ->required(),
                Select::make('experience_level')
                    ->options([
                        'entry' => 'Entry',
                        'mid' => 'Mid',
                        'senior' => 'Senior',
                        'lead' => 'Lead',
                    ]),
                Toggle::make('is_remote')->label('Remote'),
                DateTimePicker::make('closes_at')->label('Closes at')->seconds(false),
            ]),
            FormSchemas::publishSection(),
            FormSchemas::translationTabs(fn (string $locale) => FormSchemas::careerFields($locale, true)),
            ...FormSchemas::seoSections(),
        ]);
    }
}
