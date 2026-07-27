<?php

namespace App\Filament\Resources\HomeSections\Pages;

use App\Filament\Concerns\PreparesAboutSnippetSettings;
use App\Filament\Concerns\SyncsHomeSectionSlides;
use App\Filament\Concerns\SyncsModelTranslations;
use App\Filament\Pages\ManageFoundation;
use App\Filament\Resources\HomeSections\HomeSectionResource;
use App\Models\HomeSection;
use App\Models\HomeSectionTranslation;
use App\Services\HomePageService;
use App\Support\AboutSectionStats;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditHomeSection extends EditRecord
{
    use PreparesAboutSnippetSettings;
    use SyncsHomeSectionSlides;
    use SyncsModelTranslations;

    protected static string $resource = HomeSectionResource::class;

    /** @var array<string, mixed>|null */
    protected ?array $cachedStructuredSettings = null;

    public function mount(int|string $record): void
    {
        $section = HomeSection::query()->findOrFail($record);

        if ($section->type === 'foundation') {
            $this->redirect(ManageFoundation::getUrl());

            return;
        }

        parent::mount($record);
    }

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
            $data = $this->prepareAboutSnippetSettings($data);
        }

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        [$this->cachedSlides, $data] = $this->extractSlides($data);

        [$translations, $data] = $this->extractTranslations($data);

        $this->cachedTranslations = $translations;

        $data = $this->prepareAboutSnippetSettings($data);

        $type = $data['type'] ?? $this->record?->type;
        if ($type === 'about_snippet') {
            $this->cachedStructuredSettings = is_array($data['settings'] ?? null)
                ? $data['settings']
                : null;
        }

        return $data;
    }

    protected function afterSave(): void
    {
        if ($this->record->type === 'hero') {
            $this->syncSlides($this->record, $this->cachedSlides);
        }

        if ($this->record->type === 'about_snippet') {
            $this->persistAboutSnippetSettings();
        }

        $this->syncTranslations(
            $this->record,
            HomeSectionTranslation::class,
            'home_section_id',
            $this->cachedTranslations ?? [],
            ['title', 'subtitle', 'content', 'cta_label', 'cta_url'],
        );
    }

    protected function persistAboutSnippetSettings(): void
    {
        $settings = $this->normalizeAboutSettings(
            $this->cachedStructuredSettings
            ?? data_get($this->form->getState(), 'settings')
            ?? $this->record->settings
            ?? [],
        );

        $this->record->update(['settings' => $settings]);
        app(HomePageService::class)->clearCache();
    }

    /**
     * @param  array<string, mixed>  $settings
     * @return array<string, mixed>
     */
    protected function normalizeAboutSettings(array $settings): array
    {
        $settings = AboutSectionStats::normalizeSettings($settings);

        foreach (['ar', 'en'] as $locale) {
            if (count($settings['stats'][$locale] ?? []) < 4) {
                $settings['stats'][$locale] = AboutSectionStats::defaultStatsForLocale($locale);
            }

            if (empty($settings['years_badge'][$locale])) {
                $settings['years_badge'][$locale] = AboutSectionStats::defaultYearsBadgeForLocale($locale);
            }
        }

        return [
            'stats' => $settings['stats'],
            'years_badge' => $settings['years_badge'],
        ];
    }

    /** @var array<string, array<string, mixed>> */
    protected array $cachedTranslations = [];
}
