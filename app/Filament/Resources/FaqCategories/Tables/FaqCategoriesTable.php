<?php

namespace App\Filament\Resources\FaqCategories\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class FaqCategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->columns([
                TextColumn::make('sort_order')->label('#')->sortable(),
                TextColumn::make('key')->label('Key')->searchable()->sortable(),
                TextColumn::make('title_ar')
                    ->label('Title (AR)')
                    ->getStateUsing(fn ($record) => $record?->translate('ar')?->title ?? '—')
                    ->searchable(),
                TextColumn::make('title_en')
                    ->label('Title (EN)')
                    ->getStateUsing(fn ($record) => $record?->translate('en')?->title ?? '—')
                    ->toggleable(),
                TextColumn::make('faqs_count')
                    ->label('Questions')
                    ->counts('faqs')
                    ->sortable(),
                IconColumn::make('is_published')->boolean()->label('Published'),
                TextColumn::make('updated_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->recordActions([EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }
}
