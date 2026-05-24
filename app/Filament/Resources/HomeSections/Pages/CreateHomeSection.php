<?php

namespace App\Filament\Resources\HomeSections\Pages;

use App\Filament\Concerns\SyncsHomeSectionSlides;
use App\Filament\Concerns\SyncsModelTranslations;
use App\Filament\Resources\HomeSections\HomeSectionResource;
use App\Models\HomeSectionTranslation;
use Filament\Resources\Pages\CreateRecord;

class CreateHomeSection extends CreateRecord
{
    use SyncsHomeSectionSlides;
    use SyncsModelTranslations;

    protected static string $resource = HomeSectionResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        [$this->cachedSlides, $data] = $this->extractSlides($data);
        [$translations, $data] = $this->extractTranslations($data);
        $this->cachedTranslations = $translations;

        return $data;
    }

    protected function afterCreate(): void
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
