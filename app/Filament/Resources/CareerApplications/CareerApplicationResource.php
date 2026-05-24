<?php

namespace App\Filament\Resources\CareerApplications;

use App\Filament\Navigation\NavigationGroup;
use App\Filament\Resources\CareerApplications\Pages\EditCareerApplication;
use App\Filament\Resources\CareerApplications\Pages\ListCareerApplications;
use App\Filament\Resources\CareerApplications\Schemas\CareerApplicationForm;
use App\Filament\Resources\CareerApplications\Tables\CareerApplicationsTable;
use App\Models\CareerApplication;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class CareerApplicationResource extends Resource
{
    protected static ?string $model = CareerApplication::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentText;

    protected static string|UnitEnum|null $navigationGroup = NavigationGroup::ENGAGEMENT;

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return CareerApplicationForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CareerApplicationsTable::configure($table);
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCareerApplications::route('/'),
            'edit' => EditCareerApplication::route('/{record}/edit'),
        ];
    }
}
