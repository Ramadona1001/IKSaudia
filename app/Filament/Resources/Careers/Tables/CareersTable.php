<?php

namespace App\Filament\Resources\Careers\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CareersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->columns([
                TextColumn::make('sort_order')->label('#')->sortable(),
                TextColumn::make('title')->label('Title (AR)')->getStateUsing(fn ($r) => $r?->translate('ar')?->title ?? '—')->searchable(),
                TextColumn::make('department')->toggleable(),
                TextColumn::make('location')->toggleable(),
                TextColumn::make('employment_type')->label('Type')->badge(),
                IconColumn::make('is_remote')->boolean()->label('Remote'),
                IconColumn::make('is_published')->boolean()->label('Published'),
                TextColumn::make('closes_at')->dateTime()->sortable(),
            ])
            ->recordActions([EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }
}
