<?php

namespace App\Filament\Resources\ProductSpecDownloadRequests\Tables;

use App\Models\ProductSpecDownloadRequest;
use App\Services\ProductSpecDownloadService;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProductSpecDownloadRequestsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('reference_number')->label('Reference')->searchable()->sortable(),
                TextColumn::make('product_title')
                    ->label('Product')
                    ->getStateUsing(fn (ProductSpecDownloadRequest $record) => $record->product?->translate($record->locale)?->title ?? '—')
                    ->searchable(false),
                TextColumn::make('name')->searchable(),
                TextColumn::make('email')->searchable(),
                TextColumn::make('company')->toggleable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->recordActions([
                Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (ProductSpecDownloadRequest $record): bool => $record->isPending())
                    ->action(function (ProductSpecDownloadRequest $record): void {
                        app(ProductSpecDownloadService::class)->approve($record, auth()->user());

                        Notification::make()
                            ->title('Request approved')
                            ->body('A download link was emailed to the applicant.')
                            ->success()
                            ->send();
                    }),
                Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn (ProductSpecDownloadRequest $record): bool => $record->isPending())
                    ->action(function (ProductSpecDownloadRequest $record): void {
                        app(ProductSpecDownloadService::class)->reject($record, auth()->user());

                        Notification::make()
                            ->title('Request rejected')
                            ->success()
                            ->send();
                    }),
                EditAction::make(),
            ]);
    }
}
