<?php

namespace App\Filament\Resources\CareerApplications\Tables;

use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CareerApplicationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('reference_number')->label('Reference')->searchable()->sortable(),
                TextColumn::make('full_name')->label('Applicant')->searchable(['first_name', 'last_name']),
                TextColumn::make('email')->searchable(),
                TextColumn::make('career.title')
                    ->label('Career')
                    ->getStateUsing(fn ($r) => $r?->career?->translate('ar')?->title ?? '—'),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'new' => 'info',
                        'reviewing' => 'warning',
                        'shortlisted', 'interview' => 'primary',
                        'offered', 'hired' => 'success',
                        'rejected' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->recordActions([EditAction::make()]);
    }
}
