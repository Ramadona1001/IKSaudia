<?php

namespace App\Filament\Resources\Galleries\Schemas;

use App\Filament\Support\FormSchemas;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;

class GalleryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Gallery')->columns(2)->schema([
                TextInput::make('key')
                    ->label('Key')
                    ->required()
                    ->maxLength(100)
                    ->unique(ignoreRecord: true)
                    ->helperText('Unique identifier, e.g. main, projects'),
            ]),
            FormSchemas::publishSection(),
            FormSchemas::translationTabs(fn (string $locale) => [
                TextInput::make("translations.{$locale}.title")
                    ->label(__('cms.fields.title'))
                    ->required($locale === 'ar')
                    ->maxLength(255),
                Textarea::make("translations.{$locale}.description")
                    ->label(__('cms.fields.summary'))
                    ->rows(3)
                    ->columnSpanFull(),
            ]),
            Section::make('Media items')->schema([
                Repeater::make('items')
                    ->label('Gallery items')
                    ->schema([
                        Select::make('media_type')
                            ->label('Media type')
                            ->options([
                                'image' => 'Image',
                                'video_file' => 'Video upload',
                                'video_youtube' => 'YouTube link',
                            ])
                            ->default('image')
                            ->live()
                            ->required(),
                        FileUpload::make('file_path')
                            ->label('Image / video file')
                            ->disk('public')
                            ->directory('galleries')
                            ->visibility('public')
                            ->visible(fn ($get) => in_array($get('media_type'), ['image', 'video_file'], true))
                            ->image(fn ($get) => $get('media_type') === 'image')
                            ->acceptedFileTypes(fn ($get) => $get('media_type') === 'video_file'
                                ? ['video/mp4', 'video/webm', 'video/quicktime']
                                : config('security.uploads.allowed_mimes'))
                            ->maxSize(fn ($get) => $get('media_type') === 'video_file' ? 51200 : config('security.uploads.max_image_kb', 5120))
                            ->downloadable()
                            ->openable()
                            ->columnSpanFull(),
                        TextInput::make('youtube_url')
                            ->label('YouTube URL')
                            ->url()
                            ->visible(fn ($get) => $get('media_type') === 'video_youtube')
                            ->columnSpanFull(),
                        FileUpload::make('thumbnail_path')
                            ->label('Thumbnail (optional)')
                            ->image()
                            ->disk('public')
                            ->directory('galleries/thumbnails')
                            ->visibility('public')
                            ->visible(fn ($get) => in_array($get('media_type'), ['video_file', 'video_youtube'], true))
                            ->columnSpanFull(),
                        Tabs::make('Item text')->tabs([
                            Tab::make('Arabic')->schema([
                                TextInput::make('translations.ar.title')->label('Title (AR)'),
                                Textarea::make('translations.ar.caption')->label('Caption (AR)')->rows(2),
                            ]),
                            Tab::make('English')->schema([
                                TextInput::make('translations.en.title')->label('Title (EN)'),
                                Textarea::make('translations.en.caption')->label('Caption (EN)')->rows(2),
                            ]),
                        ])->columnSpanFull(),
                        Toggle::make('is_published')->label('Published')->default(true),
                        TextInput::make('sort_order')->numeric()->default(0),
                    ])
                    ->collapsible()
                    ->itemLabel(fn (array $state): ?string => $state['translations']['en']['title']
                        ?? $state['translations']['ar']['title']
                        ?? $state['media_type']
                        ?? 'Item')
                    ->defaultItems(0)
                    ->columnSpanFull(),
            ]),
        ]);
    }
}
