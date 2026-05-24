<?php

namespace App\Filament\Resources\Pages\Pages;

use App\Filament\Concerns\SyncsModelTranslations;
use App\Filament\Resources\Pages\PageResource;
use App\Models\PageTranslation;
use App\Models\SeoMeta;
use Filament\Resources\Pages\CreateRecord;

class CreatePage extends CreateRecord
{
    use SyncsModelTranslations;

    protected static string $resource = PageResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        [$translations, $data] = $this->extractTranslations($data);
        $this->cachedTranslations = $translations;
        $this->cachedSeo = $data['seo'] ?? [];
        unset($data['seo']);

        return $data;
    }

    protected function afterCreate(): void
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
            if (! is_array($data) || empty(array_filter($data))) {
                continue;
            }

            SeoMeta::query()->updateOrCreate(
                [
                    'seoable_type' => $record::class,
                    'seoable_id' => $record->getKey(),
                    'locale' => $locale,
                ],
                $data,
            );
        }
    }
}
