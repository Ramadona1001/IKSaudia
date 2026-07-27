<?php

namespace App\Filament\Resources\Partners\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PartnersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->columns([
                TextColumn::make('sort_order')->label('#')->sortable(),
                TextColumn::make('name')->label('Name (AR)')->getStateUsing(fn ($r) => $r?->translate('ar')?->name ?? '—')->searchable(),
                TextColumn::make('website_url')
                    ->label('Website')
                    ->url(fn (?string $state): ?string => filled($state) ? $state : null)
                    ->openUrlInNewTab()
                    ->toggleable(),
                IconColumn::make('is_published')->boolean()->label('Published'),
                TextColumn::make('updated_at')->dateTime()->sortable(),
            ])
            ->recordActions([EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }
}
