<?php

namespace App\Filament\Resources\Pages\Pages;

use App\Filament\Concerns\SyncsModelTranslations;
use App\Filament\Resources\Pages\PageResource;
use App\Models\PageTranslation;
use App\Models\SeoMeta;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPage extends EditRecord
{
    use SyncsModelTranslations;

    protected static string $resource = PageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $record = $this->getRecord();
        $record->load(['translations', 'seoMeta']);

        foreach (['ar', 'en'] as $locale) {
            $translation = $record->translationFor($locale);
            $data['translations'][$locale] = [
                'title' => $translation?->title,
                'slug' => $translation?->slug,
                'excerpt' => $translation?->excerpt,
                'body' => $translation?->body,
            ];

            $seo = $record->seoFor($locale);
            $data['seo'][$locale] = [
                'meta_title' => $seo?->meta_title,
                'meta_description' => $seo?->meta_description,
                'og_image' => $seo?->og_image,
            ];
        }

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        [$translations, $data] = $this->extractTranslations($data);
        $this->cachedTranslations = $translations;
        $this->cachedSeo = $data['seo'] ?? [];
        unset($data['seo']);

        return $data;
    }

    protected function afterSave(): void
    {
        $this->syncTranslations(
            $this->record,
            PageTranslation::class,
            'page_id',
            $this->cachedTranslations ?? [],
            ['title', 'slug', 'excerpt', 'body'],
        );

        $this->syncSeo($this->record, $this->cachedSeo ?? []);
    }

    /** @var array<string, array<string, mixed>> */
    protected array $cachedTranslations = [];

    /** @var array<string, array<string, mixed>> */
    protected array $cachedSeo = [];

    protected function syncSeo($record, array $seo): void
    {
        foreach ($seo as $locale => $data) {
            if (! is_array($data)) {
                continue;
            }

            SeoMeta::query()->updateOrCreate(
                [
                    'seoable_type' => $record::class,
                    'seoable_id' => $record->getKey(),
                    'locale' => $locale,
                ],
                array_filter($data, fn ($v) => $v !== null && $v !== ''),
            );
        }
    }
}
