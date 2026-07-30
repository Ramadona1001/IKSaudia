<?php

namespace App\Filament\Support;

use App\Support\BootstrapIcon;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;

final class FormSchemas
{
    public static function featuredImageUpload(string $directory): FileUpload
    {
        return FileUpload::make('featured_image')
            ->label(__('cms.fields.featured_image'))
            ->image()
            ->disk('public')
            ->directory($directory)
            ->visibility('public')
            ->maxSize(config('security.uploads.max_image_kb', 5120))
            ->acceptedFileTypes(config('security.uploads.allowed_mimes', ['image/jpeg', 'image/png', 'image/webp']))
            ->downloadable(false)
            ->openable(false)
            ->imageEditor()
            ->imageEditorAspectRatios(['16:9', '4:3', '1:1', null])
            ->columnSpanFull();
    }

    /**
     * @param  callable(string $locale): array  $fieldsFactory
     */
    public static function translationTabs(callable $fieldsFactory): Tabs
    {
        return Tabs::make('Translations')->tabs([
            Tab::make(__('cms.tabs.arabic'))->schema($fieldsFactory('ar')),
            Tab::make(__('cms.tabs.english'))->schema($fieldsFactory('en')),
        ]);
    }

    public static function publishSection(): Section
    {
        return Section::make(__('cms.sections.publishing'))
            ->columns(2)
            ->schema([
                Toggle::make('is_published')->label(__('cms.fields.published'))->default(false),
                DateTimePicker::make('published_at')->label(__('cms.fields.publish_at'))->seconds(false),
                TextInput::make('sort_order')->numeric()->default(0),
            ]);
    }

    public static function seoSections(): array
    {
        return [
            Section::make(__('cms.sections.seo_ar'))->collapsed()->schema(self::seoFields('ar')),
            Section::make(__('cms.sections.seo_en'))->collapsed()->schema(self::seoFields('en')),
        ];
    }

    public static function seoFields(string $locale): array
    {
        return [
            TextInput::make("seo.{$locale}.meta_title")->label(__('cms.fields.meta_title'))->maxLength(70),
            Textarea::make("seo.{$locale}.meta_description")->label(__('cms.fields.meta_description'))->rows(2)->maxLength(160),
            TextInput::make("seo.{$locale}.og_image")->label(__('cms.fields.og_image'))->url(),
        ];
    }

    public static function localeFields(string $locale, array $fields): array
    {
        return collect($fields)->map(function ($field) use ($locale) {
            return $field->name("translations.{$locale}.{$field->getName()}");
        })->all();
    }

    public static function contentFields(string $locale, bool $requireArabic = false): array
    {
        return [
            TextInput::make("translations.{$locale}.title")
                ->label(__('cms.fields.title'))
                ->required($requireArabic && $locale === 'ar')
                ->maxLength(255),
            TextInput::make("translations.{$locale}.slug")
                ->label(__('cms.fields.slug'))
                ->required($requireArabic && $locale === 'ar')
                ->maxLength(255),
            Textarea::make("translations.{$locale}.summary")
                ->label(__('cms.fields.summary'))
                ->rows(3)
                ->columnSpanFull(),
            RichEditor::make("translations.{$locale}.body")
                ->label(__('cms.fields.body'))
                ->columnSpanFull(),
        ];
    }

    public static function simpleNameFields(string $locale): array
    {
        return [
            TextInput::make("translations.{$locale}.name")
                ->label(__('cms.fields.name'))
                ->required($locale === 'ar')
                ->maxLength(255),
            Textarea::make("translations.{$locale}.description")
                ->label(__('cms.fields.summary'))
                ->rows(3)
                ->columnSpanFull(),
        ];
    }

    public static function newsFields(string $locale, bool $requireArabic = false): array
    {
        return [
            TextInput::make("translations.{$locale}.title")->required($requireArabic && $locale === 'ar')->maxLength(255),
            TextInput::make("translations.{$locale}.slug")->required($requireArabic && $locale === 'ar')->maxLength(255),
            Textarea::make("translations.{$locale}.excerpt")->rows(2)->columnSpanFull(),
            RichEditor::make("translations.{$locale}.body")->columnSpanFull(),
        ];
    }

    public static function careerFields(string $locale, bool $requireArabic = false): array
    {
        return [
            TextInput::make("translations.{$locale}.title")->required($requireArabic && $locale === 'ar')->maxLength(255),
            TextInput::make("translations.{$locale}.slug")->required($requireArabic && $locale === 'ar')->maxLength(255),
            Textarea::make("translations.{$locale}.summary")->rows(2)->columnSpanFull(),
            RichEditor::make("translations.{$locale}.requirements")->label('Requirements')->columnSpanFull(),
            RichEditor::make("translations.{$locale}.responsibilities")->label('Responsibilities')->columnSpanFull(),
            RichEditor::make("translations.{$locale}.benefits")->label('Benefits')->columnSpanFull(),
        ];
    }

    public static function certificationFields(string $locale, bool $requireArabic = false): array
    {
        return [
            TextInput::make("translations.{$locale}.title")->required($requireArabic && $locale === 'ar')->maxLength(255),
            TextInput::make("translations.{$locale}.slug")->required($requireArabic && $locale === 'ar')->maxLength(255),
            Textarea::make("translations.{$locale}.description")->rows(4)->columnSpanFull(),
        ];
    }

    public static function bootstrapIconSelect(string $name = 'icon', ?string $default = null, ?string $label = null): Select
    {
        $field = Select::make($name)
            ->label($label ?? 'Icon')
            ->options(BootstrapIcon::groupedOptions())
            ->searchable()
            ->native(false)
            ->dehydrateStateUsing(fn (?string $state): ?string => BootstrapIcon::normalize($state))
            ->afterStateHydrated(function (Select $component, $state): void {
                if (! is_string($state) || $state === '') {
                    return;
                }

                $normalized = BootstrapIcon::normalize($state);

                if ($normalized !== null && $normalized !== $state) {
                    $component->state($normalized);
                }
            })
            ->helperText('Bootstrap icon shown on the website (menus, cards, etc.).');

        if ($default !== null) {
            $field->default($default);
        }

        return $field;
    }
}
