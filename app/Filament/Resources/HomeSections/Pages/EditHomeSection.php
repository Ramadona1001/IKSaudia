<?php

namespace App\Filament\Resources\HomeSections\Pages;

use App\Filament\Concerns\SyncsHomeSectionSlides;
use App\Filament\Concerns\SyncsModelTranslations;
use App\Filament\Resources\HomeSections\HomeSectionResource;
use App\Models\HomeSectionTranslation;
use App\Support\AboutSectionStats;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditHomeSection extends EditRecord
{
    use SyncsHomeSectionSlides;
    use SyncsModelTranslations;

    protected static string $resource = HomeSectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $this->getRecord()->load(['translations', 'items.translations']);

        foreach (['ar', 'en'] as $locale) {
            $translation = $this->getRecord()->translationFor($locale);
            $data['translations'][$locale] = [
                'title' => $translation?->title,
                'subtitle' => $translation?->subtitle,
                'content' => $translation?->bodyText(),
                'cta_label' => $translation?->cta_label,
                'cta_url' => $translation?->cta_url,
            ];
        }

        if ($this->getRecord()->type === 'hero') {
            $data['slides'] = $this->mapSlidesForForm($this->getRecord());
        }

        if ($this->getRecord()->type === 'about_snippet') {
            $settings = AboutSectionStats::normalizeSettings(
                is_array($data['settings'] ?? null) ? $data['settings'] : [],
            );

            foreach (['ar', 'en'] as $locale) {
                if (count($settings['stats'][$locale] ?? []) < 4) {
                    $settings['stats'][$locale] = AboutSectionStats::defaultStatsForLocale($locale);
                }
                if (empty($settings['years_badge'][$locale])) {
                    $settings['years_badge'][$locale] = AboutSectionStats::defaultYearsBadgeForLocale($locale);
                }
            }

            $data['settings'] = $settings;
        }

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        [$this->cachedSlides, $data] = $this->extractSlides($data);

        [$translations, $data] = $this->extractTranslations($data);

        $this->cachedTranslations = $translations;

        return $data;
    }

    protected function afterSave(): void
    {
        if ($this->record->type === 'hero') {
            $this->syncSlides($this->record, $this->cachedSlides);
        }

        $this->syncTranslations(
            $this->record,
            HomeSectionTranslation::class,
            'home_section_id',
            $this->cachedTranslations ?? [],
            ['title', 'subtitle', 'content', 'cta_label', 'cta_url'],
        );
    }

    /** @var array<string, array<string, mixed>> */
    protected array $cachedTranslations = [];
}
