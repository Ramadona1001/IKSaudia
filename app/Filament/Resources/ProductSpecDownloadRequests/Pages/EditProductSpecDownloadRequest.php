<?php

namespace App\Filament\Resources\ProductSpecDownloadRequests\Pages;

use App\Filament\Resources\ProductSpecDownloadRequests\ProductSpecDownloadRequestResource;
use App\Services\ProductSpecDownloadService;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditProductSpecDownloadRequest extends EditRecord
{
    protected static string $resource = ProductSpecDownloadRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('approve')
                ->label('Approve')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->requiresConfirmation()
                ->visible(fn (): bool => $this->record->isPending())
                ->action(function (): void {
                    app(ProductSpecDownloadService::class)->approve($this->record, auth()->user());

                    Notification::make()
                        ->title('Request approved')
                        ->body('A download link was emailed to the applicant.')
                        ->success()
                        ->send();

                    $this->refreshFormData(['status', 'download_token', 'approved_at', 'admin_notes']);
                }),
            Action::make('reject')
                ->label('Reject')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->requiresConfirmation()
                ->visible(fn (): bool => $this->record->isPending())
                ->action(function (): void {
                    app(ProductSpecDownloadService::class)->reject(
                        $this->record,
                        auth()->user(),
                        $this->data['admin_notes'] ?? null,
                    );

                    Notification::make()
                        ->title('Request rejected')
                        ->success()
                        ->send();

                    $this->refreshFormData(['status', 'rejected_at', 'admin_notes']);
                }),
            DeleteAction::make(),
        ];
    }
}
