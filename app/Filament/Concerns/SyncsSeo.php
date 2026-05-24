<?php

namespace App\Filament\Concerns;

use App\Models\SeoMeta;
use Illuminate\Database\Eloquent\Model;

trait SyncsSeo
{
    /** @var array<string, array<string, mixed>> */
    protected array $cachedSeo = [];

    protected function extractSeo(array &$data): array
    {
        $seo = $data['seo'] ?? [];
        unset($data['seo']);

        return is_array($seo) ? $seo : [];
    }

    protected function syncSeoForRecord(Model $record, array $seo): void
    {
        foreach ($seo as $locale => $data) {
            if (! is_array($data)) {
                continue;
            }

            $payload = array_filter($data, fn ($v) => $v !== null && $v !== '');

            if ($payload === []) {
                continue;
            }

            SeoMeta::query()->updateOrCreate(
                [
                    'seoable_type' => $record::class,
                    'seoable_id' => $record->getKey(),
                    'locale' => $locale,
                ],
                $payload,
            );
        }
    }

    protected function fillSeoFromRecord(Model $record, array $data, array $locales = ['ar', 'en']): array
    {
        $record->loadMissing('seoMeta');

        foreach ($locales as $locale) {
            $seo = method_exists($record, 'seoFor')
                ? $record->seoFor($locale)
                : $record->seoMeta->firstWhere('locale', $locale);

            $data['seo'][$locale] = [
                'meta_title' => $seo?->meta_title,
                'meta_description' => $seo?->meta_description,
                'og_image' => $seo?->og_image,
            ];
        }

        return $data;
    }
}
