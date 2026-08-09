<?php

namespace App\Filament\Resources\ProductSpecDownloadRequests;

use App\Filament\Navigation\NavigationGroup;
use App\Filament\Resources\ProductSpecDownloadRequests\Pages\EditProductSpecDownloadRequest;
use App\Filament\Resources\ProductSpecDownloadRequests\Pages\ListProductSpecDownloadRequests;
use App\Filament\Resources\ProductSpecDownloadRequests\Schemas\ProductSpecDownloadRequestForm;
use App\Filament\Resources\ProductSpecDownloadRequests\Tables\ProductSpecDownloadRequestsTable;
use App\Models\ProductSpecDownloadRequest;
use Illuminate\Database\Eloquent\Builder;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class ProductSpecDownloadRequestResource extends Resource
{
    protected static ?string $model = ProductSpecDownloadRequest::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentArrowDown;

    protected static string|UnitEnum|null $navigationGroup = NavigationGroup::ENGAGEMENT;

    protected static ?string $navigationLabel = 'PDF download requests';

    protected static ?int $navigationSort = 3;

    public static function getModelLabel(): string
    {
        return 'PDF download request';
    }

    public static function getPluralModelLabel(): string
    {
        return 'PDF download requests';
    }

    public static function getNavigationBadge(): ?string
    {
        $count = static::getModel()::query()->where('status', 'pending')->count();

        return $count > 0 ? (string) $count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with(['product.translations']);
    }

    public static function form(Schema $schema): Schema
    {
        return ProductSpecDownloadRequestForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProductSpecDownloadRequestsTable::configure($table);
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProductSpecDownloadRequests::route('/'),
            'edit' => EditProductSpecDownloadRequest::route('/{record}/edit'),
        ];
    }
}
