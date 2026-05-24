<?php

namespace App\Filament\Resources\Redirects\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class RedirectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Redirect')->columns(2)->schema([
                TextInput::make('from_path')
                    ->label('From path')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('/old-url'),
                TextInput::make('to_path')
                    ->label('To path')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('/new-url'),
                Select::make('status_code')
                    ->options([
                        301 => '301 — Permanent',
                        302 => '302 — Temporary',
                        307 => '307 — Temporary',
                        308 => '308 — Permanent',
                    ])
                    ->default(301)
                    ->required(),
                Toggle::make('is_active')->label('Active')->default(true),
            ]),
        ]);
    }
}
