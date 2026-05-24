<?php

namespace App\Filament\Resources\Services\Pages;

use App\Filament\Concerns\SyncsModelTranslations;
use App\Filament\Resources\Services\ServiceResource;
use App\Models\SeoMeta;
use App\Models\ServiceTranslation;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditService extends EditRecord
{
    use SyncsModelTranslations;

    protected static string $resource = ServiceResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $this->getRecord()->load(['translations', 'seoMeta']);

        foreach (['ar', 'en'] as $locale) {
            $t = $this->getRecord()->translationFor($locale);
            $data['translations'][$locale] = [
                'title' => $t?->title,
                'slug' => $t?->slug,
                'summary' => $t?->summary,
                'body' => $t?->body,
                'cta_label' => $t?->cta_label,
                'cta_url' => $t?->cta_url,
            ];

            $seo = $this->getRecord()->seoFor($locale);
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
            ServiceTranslation::class,
            'service_id',
            $this->cachedTranslations ?? [],
            ['title', 'slug', 'summary', 'body', 'cta_label', 'cta_url'],
        );

        foreach ($this->cachedSeo ?? [] as $locale => $data) {
            if (! is_array($data)) {
                continue;
            }

            SeoMeta::query()->updateOrCreate(
                [
                    'seoable_type' => $this->record::class,
                    'seoable_id' => $this->record->getKey(),
                    'locale' => $locale,
                ],
                array_filter($data, fn ($v) => $v !== null && $v !== ''),
            );
        }
    }

    /** @var array<string, array<string, mixed>> */
    protected array $cachedTranslations = [];

    /** @var array<string, array<string, mixed>> */
    protected array $cachedSeo = [];
}
