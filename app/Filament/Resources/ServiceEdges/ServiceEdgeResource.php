<?php

namespace App\Filament\Resources\ServiceEdges;

use App\Filament\Navigation\NavigationGroup;
use App\Filament\Resources\ServiceEdges\Pages\CreateServiceEdge;
use App\Filament\Resources\ServiceEdges\Pages\EditServiceEdge;
use App\Filament\Resources\ServiceEdges\Pages\ListServiceEdges;
use App\Filament\Resources\ServiceEdges\Schemas\ServiceEdgeForm;
use App\Filament\Resources\ServiceEdges\Tables\ServiceEdgesTable;
use App\Models\ServiceEdge;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class ServiceEdgeResource extends Resource
{
    protected static ?string $model = ServiceEdge::class;

    protected static ?string $navigationLabel = 'Why choose us';

    protected static ?string $modelLabel = 'advantage';

    protected static ?string $pluralModelLabel = 'Why choose us';

    protected static ?string $navigationParentItem = 'Services';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedStar;

    protected static string|UnitEnum|null $navigationGroup = NavigationGroup::CONTENT;

    protected static ?int $navigationSort = 3;

    public static function getNavigationLabel(): string
    {
        return __('cms.nav.service_edges');
    }

    public static function getModelLabel(): string
    {
        return __('cms.nav.service_edge');
    }

    public static function getPluralModelLabel(): string
    {
        return __('cms.nav.service_edges');
    }

    public static function form(Schema $schema): Schema
    {
        return ServiceEdgeForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ServiceEdgesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListServiceEdges::route('/'),
            'create' => CreateServiceEdge::route('/create'),
            'edit' => EditServiceEdge::route('/{record}/edit'),
        ];
    }
}
