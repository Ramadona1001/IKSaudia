<?php

namespace App\Filament\Resources\Services\Pages;

use App\Filament\Concerns\PreparesPublishableAttributes;
use App\Filament\Concerns\SyncsModelTranslations;
use App\Filament\Resources\Services\ServiceResource;
use App\Models\SeoMeta;
use App\Models\ServiceTranslation;
use Filament\Resources\Pages\CreateRecord;

class CreateService extends CreateRecord
{
    use PreparesPublishableAttributes;
    use SyncsModelTranslations;

    protected static string $resource = ServiceResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        [$translations, $data] = $this->extractTranslations($data);
        $this->cachedTranslations = $translations;
        $this->cachedSeo = $data['seo'] ?? [];
        unset($data['seo']);

        return $this->preparePublishableAttributes($data);
    }

    protected function afterCreate(): void
    {
        $this->syncTranslations(
            $this->record,
            ServiceTranslation::class,
            'service_id',
            $this->cachedTranslations ?? [],
            ['title', 'slug', 'summary', 'body', 'cta_label', 'cta_url'],
        );

        $this->syncSeo($this->cachedSeo ?? []);
    }

    /** @var array<string, array<string, mixed>> */
    protected array $cachedTranslations = [];

    /** @var array<string, array<string, mixed>> */
    protected array $cachedSeo = [];

    protected function syncSeo(array $seo): void
    {
        foreach ($seo as $locale => $data) {
            if (! is_array($data) || empty(array_filter($data))) {
                continue;
            }

            SeoMeta::query()->updateOrCreate(
                [
                    'seoable_type' => $this->record::class,
                    'seoable_id' => $this->record->getKey(),
                    'locale' => $locale,
                ],
                $data,
            );
        }
    }
}
