<?php

namespace App\Filament\Resources\CareerApplications\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CareerApplicationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Review')->schema([
                Select::make('status')
                    ->options([
                        'new' => 'New',
                        'reviewing' => 'Reviewing',
                        'shortlisted' => 'Shortlisted',
                        'interview' => 'Interview',
                        'offered' => 'Offered',
                        'rejected' => 'Rejected',
                        'hired' => 'Hired',
                    ])
                    ->required(),
                Textarea::make('admin_notes')->label('Admin notes')->rows(4)->columnSpanFull(),
            ]),
            Section::make('Applicant')->columns(2)->schema([
                TextInput::make('reference_number')->disabled(),
                TextInput::make('first_name')->disabled(),
                TextInput::make('last_name')->disabled(),
                TextInput::make('email')->disabled(),
                TextInput::make('phone')->disabled(),
                TextInput::make('nationality')->disabled(),
                TextInput::make('linkedin_url')->label('LinkedIn')->disabled()->columnSpanFull(),
                Textarea::make('cover_letter')->disabled()->rows(4)->columnSpanFull(),
                TextInput::make('resume_path')->label('Resume path')->disabled()->columnSpanFull(),
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
