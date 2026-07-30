<?php

namespace App\Filament\Resources\ContactSubmissions\Schemas;

use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ContactSubmissionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Review')->schema([
                Select::make('status')
                    ->options([
                        'new' => 'New',
                        'read' => 'Read',
                        'replied' => 'Replied',
                        'archived' => 'Archived',
                        'spam' => 'Spam',
                    ])
                    ->required(),
                Textarea::make('admin_notes')->label('Admin notes')->rows(4)->columnSpanFull(),
            ]),
            Section::make('Submission')->columns(2)->schema([
                TextInput::make('reference_number')->disabled(),
                TextInput::make('name')->disabled(),
                TextInput::make('email')->disabled(),
                TextInput::make('phone')->disabled(),
                TextInput::make('company')->disabled(),
                TextInput::make('subject')->disabled()->columnSpanFull(),
                Textarea::make('message')->disabled()->rows(6)->columnSpanFull(),
                KeyValue::make('custom_fields')
                    ->label('Additional fields')
                    ->disabled()
                    ->columnSpanFull()
                    ->visible(fn ($record) => is_array($record?->custom_fields) && $record->custom_fields !== []),
            ]),
            Section::make('Meta')->columns(2)->collapsed()->schema([
                TextInput::make('locale')->disabled(),
                TextInput::make('ip_address')->disabled(),
                Textarea::make('user_agent')->disabled()->rows(2)->columnSpanFull(),
                TextInput::make('created_at')->disabled(),
            ]),
        ]);
    }
}
