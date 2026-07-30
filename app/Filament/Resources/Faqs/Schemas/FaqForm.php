<?php

namespace App\Filament\Resources\Faqs\Schemas;

use App\Filament\Support\FormSchemas;
use App\Models\FaqCategory;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class FaqForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('FAQ details')->columns(2)->schema([
                Select::make('faq_category_id')
                    ->label('Category')
                    ->options(fn (): array => FaqCategory::query()
                        ->orderBy('sort_order')
                        ->with('translations')
                        ->get()
                        ->mapWithKeys(fn (FaqCategory $category): array => [
                            $category->id => $category->translate('en')?->title
                                ?? $category->translate('ar')?->title
                                ?? $category->key,
                        ])
                        ->all())
                    ->searchable()
                    ->required()
                    ->columnSpanFull(),
            ]),
            Section::make('Publishing')->columns(2)->schema([
                Toggle::make('is_published')->label('Published')->default(true),
                TextInput::make('sort_order')->numeric()->default(0),
            ]),
            FormSchemas::translationTabs(fn (string $locale) => [
                TextInput::make("translations.{$locale}.question")
                    ->label('Question')
                    ->required($locale === 'ar')
                    ->maxLength(500)
                    ->columnSpanFull(),
                Textarea::make("translations.{$locale}.answer")
                    ->label('Answer')
                    ->required($locale === 'ar')
                    ->rows(6)
                    ->columnSpanFull(),
            ]),
        ]);
    }
}
