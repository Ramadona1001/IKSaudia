<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Filament\Support\FormSchemas;
use App\Models\Product;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->columns(1)->components([
            Section::make(__('cms.sections.product_settings'))
                ->columnSpanFull()
                ->columns(2)
                ->schema([
                    Select::make('parent_id')
                        ->label(__('cms.fields.parent_category'))
                        ->options(fn (): array => Product::query()
                            ->whereNull('parent_id')
                            ->with(['translations' => fn ($q) => $q->where('locale', 'en')])
                            ->orderBy('sort_order')
                            ->get()
                            ->mapWithKeys(fn (Product $p) => [
                                $p->id => $p->translate('en')?->title ?? "Product #{$p->id}",
                            ])
                            ->all())
                        ->searchable()
                        ->nullable(),
                    TextInput::make('legacy_page_id')
                        ->label(__('cms.fields.legacy_page_id'))
                        ->numeric()
                        ->disabled()
                        ->dehydrated(),
                    FormSchemas::featuredImageUpload('products'),
                    FileUpload::make('pdf_path')
                        ->label(__('cms.fields.product_pdf'))
                        ->acceptedFileTypes(['application/pdf'])
                        ->disk('public')
                        ->directory('products/pdfs')
                        ->visibility('public')
                        ->downloadable()
                        ->openable(),
                    TextInput::make('icon')->placeholder('bi-box-seam'),
                    Toggle::make('is_featured')->label(__('cms.fields.featured')),
                ]),
            FormSchemas::publishSection()->columnSpanFull(),
            FormSchemas::translationTabs(fn (string $locale) => FormSchemas::contentFields($locale, true)),
            ...FormSchemas::seoSections(),
        ]);
    }
}
