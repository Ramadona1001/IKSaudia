<?php

namespace App\Filament\Resources\ProductSpecDownloadRequests\Schemas;

use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProductSpecDownloadRequestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Review')->schema([
                Placeholder::make('status_display')
                    ->label('Status')
                    ->content(fn ($record) => ucfirst((string) ($record?->status ?? 'pending'))),
                Textarea::make('admin_notes')
                    ->label('Admin notes')
                    ->rows(3)
                    ->columnSpanFull(),
                Placeholder::make('download_link')
                    ->label('Download link')
                    ->content(function ($record): string {
                        if (! $record?->tokenIsValid()) {
                            return '—';
                        }

                        return route('products.spec-download', ['token' => $record->download_token]);
                    })
                    ->visible(fn ($record) => $record?->isApproved()),
            ]),
            Section::make('Request details')->columns(2)->schema([
                TextInput::make('reference_number')->disabled(),
                TextInput::make('product_title')
                    ->label('Product')
                    ->disabled()
                    ->dehydrated(false)
                    ->formatStateUsing(fn ($record) => $record?->product?->translate($record->locale)?->title ?? '—'),
                TextInput::make('name')->disabled(),
                TextInput::make('email')->disabled(),
                TextInput::make('phone')->disabled(),
                TextInput::make('company')->disabled(),
                TextInput::make('locale')->disabled(),
                TextInput::make('created_at')
                    ->disabled()
                    ->formatStateUsing(fn ($state) => $state ? (string) $state : '—'),
            ]),
            Section::make('Meta')->columns(2)->collapsed()->schema([
                TextInput::make('ip_address')->disabled(),
                Textarea::make('user_agent')->disabled()->rows(2)->columnSpanFull(),
            ]),
        ]);
    }
}
