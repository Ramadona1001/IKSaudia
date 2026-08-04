<?php

namespace App\Filament\Resources\HomeSections\Pages;

use App\Filament\Concerns\PreparesAboutSnippetSettings;
use App\Filament\Concerns\SyncsHomeSectionSlides;
use App\Filament\Concerns\SyncsModelTranslations;
use App\Filament\Resources\HomeSections\HomeSectionResource;
use App\Models\HomeSectionTranslation;
use App\Support\AboutSectionStats;
use Filament\Resources\Pages\CreateRecord;

class CreateHomeSection extends CreateRecord
{
    use PreparesAboutSnippetSettings;
    use SyncsHomeSectionSlides;
    use SyncsModelTranslations;

    protected static string $resource = HomeSectionResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        [$this->cachedSlides, $data] = $this->extractSlides($data);
        [$translations, $data] = $this->extractTranslations($data);
        $this->cachedTranslations = $translations;

        if (($data['type'] ?? null) === 'about_snippet') {
            $data['settings'] = $this->aboutSnippetSettingsFromForm();
        }

        return $this->prepareAboutSnippetSettings($data);
    }

    protected function afterCreate(): void
    {
        if ($this->record->type === 'hero') {
            $this->syncSlides($this->record, $this->cachedSlides);
        }

        if ($this->record->type === 'about_snippet') {
            $settings = AboutSectionStats::sanitizeSettings(
                is_array($this->record->settings) ? $this->record->settings : $this->aboutSnippetSettingsFromForm(),
            );
            $this->record->update(['settings' => $settings]);
            app(\App\Services\HomePageService::class)->clearCache();
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
