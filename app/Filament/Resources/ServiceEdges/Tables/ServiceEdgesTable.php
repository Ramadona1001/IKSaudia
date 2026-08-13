<?php

namespace App\Filament\Resources\ServiceEdges\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ServiceEdgesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->columns([
                TextColumn::make('sort_order')->label('#')->sortable(),
                TextColumn::make('icon')
                    ->label(__('cms.fields.icon'))
                    ->toggleable(),
                TextColumn::make('title_ar')
                    ->label(__('cms.fields.title').' (AR)')
                    ->getStateUsing(fn ($record) => $record?->translate('ar')?->title ?? '—')
                    ->searchable(),
                TextColumn::make('title_en')
                    ->label(__('cms.fields.title').' (EN)')
                    ->getStateUsing(fn ($record) => $record?->translate('en')?->title ?? '—')
                    ->toggleable(),
                IconColumn::make('is_published')->boolean()->label(__('cms.fields.published')),
                TextColumn::make('updated_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordActions([EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }
}
