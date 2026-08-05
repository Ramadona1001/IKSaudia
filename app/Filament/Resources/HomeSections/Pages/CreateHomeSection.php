<?php

namespace App\Filament\Resources\HomeSections\Pages;

use App\Filament\Concerns\SyncsHomeSectionSlides;
use App\Filament\Concerns\SyncsModelTranslations;
use App\Filament\Resources\HomeSections\HomeSectionResource;
use App\Models\HomeSectionTranslation;
use App\Services\HomePageService;
use Filament\Resources\Pages\CreateRecord;

class CreateHomeSection extends CreateRecord
{
    use SyncsHomeSectionSlides;
    use SyncsModelTranslations;

    protected static string $resource = HomeSectionResource::class;

    /** @var array<string, mixed> */
    protected array $cachedAboutHeadings = [];

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        [$this->cachedSlides, $data] = $this->extractSlides($data);
        [$translations, $data] = $this->extractTranslations($data);
        $this->cachedTranslations = $translations;

        if (($data['type'] ?? null) === 'about_snippet') {
            $this->cachedAboutHeadings = is_array(data_get($data, 'settings.headings'))
                ? data_get($data, 'settings.headings')
                : [];
            unset($data['settings']);
        }

        return $data;
    }

    protected function afterCreate(): void
    {
        if ($this->record->type === 'hero') {
            $this->syncSlides($this->record, $this->cachedSlides);
        }

        if ($this->record->type === 'about_snippet' && $this->cachedAboutHeadings !== []) {
            $existing = is_array($this->record->settings) ? $this->record->settings : [];
            $this->record->update([
                'settings' => array_merge($existing, [
                    'headings' => $this->cachedAboutHeadings,
                ]),
            ]);
            app(HomePageService::class)->clearCache();
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
