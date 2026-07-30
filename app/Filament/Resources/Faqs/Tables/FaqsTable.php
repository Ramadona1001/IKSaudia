<?php

namespace App\Filament\Resources\Faqs\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class FaqsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->columns([
                TextColumn::make('sort_order')->label('#')->sortable(),
                TextColumn::make('question_ar')
                    ->label('Question (AR)')
                    ->getStateUsing(fn ($record) => $record?->translate('ar')?->question ?? '—')
                    ->searchable()
                    ->wrap(),
                TextColumn::make('category.title_ar')
                    ->label('Category')
                    ->getStateUsing(fn ($record) => $record?->category?->translate('ar')?->title ?? $record?->category?->key ?? '—')
                    ->sortable(),
                IconColumn::make('is_published')->boolean()->label('Published'),
                TextColumn::make('updated_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('faq_category_id')
                    ->label('Category')
                    ->relationship('category', 'key'),
            ])
            ->recordActions([EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }
}
