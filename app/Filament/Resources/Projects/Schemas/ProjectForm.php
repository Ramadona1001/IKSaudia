<?php

namespace App\Filament\Resources\Projects\Schemas;

use App\Filament\Support\FormSchemas;
use App\Models\Industry;
use App\Models\Service;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Project details')->columns(2)->schema([
                FormSchemas::featuredImageUpload('projects'),
                TextInput::make('client_name'),
                TextInput::make('location'),
                TextInput::make('year')->numeric()->minValue(1990)->maxValue(2100),
                DatePicker::make('completed_at'),
                Toggle::make('is_featured'),
            ]),
            FormSchemas::publishSection(),
            FormSchemas::translationTabs(fn (string $locale) => FormSchemas::contentFields($locale, true)),
            Section::make('Relations')->schema([
                CheckboxList::make('industries')
                    ->relationship('industries', modifyQueryUsing: fn ($query) => $query->with('translations'))
                    ->getOptionLabelFromRecordUsing(fn (Industry $i) => $i->translate('ar')?->title ?? "Industry #{$i->id}")
                    ->columns(2),
                CheckboxList::make('services')
                    ->relationship('services', modifyQueryUsing: fn ($query) => $query->with('translations'))
                    ->getOptionLabelFromRecordUsing(fn (Service $s) => $s->translate('ar')?->title ?? "Service #{$s->id}")
                    ->columns(2),
            ]),
            ...FormSchemas::seoSections(),
        ]);
    }
}
