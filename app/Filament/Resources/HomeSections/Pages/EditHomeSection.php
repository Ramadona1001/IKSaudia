<?php

namespace App\Filament\Resources\HomeSections\Pages;

use App\Filament\Concerns\PreparesAboutSnippetSettings;
use App\Filament\Concerns\PreparesFoundationSettings;
use App\Filament\Concerns\SyncsHomeSectionSlides;
use App\Filament\Concerns\SyncsModelTranslations;
use App\Filament\Resources\HomeSections\HomeSectionResource;
use App\Models\HomeSectionTranslation;
use App\Services\HomePageService;
use App\Support\AboutSectionStats;
use App\Support\FoundationSection;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditHomeSection extends EditRecord
{
    use PreparesAboutSnippetSettings;
    use PreparesFoundationSettings;
    use SyncsHomeSectionSlides;
    use SyncsModelTranslations;

    protected static string $resource = HomeSectionResource::class;

    /** @var array<string, mixed>|null */
    protected ?array $cachedStructuredSettings = null;

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

        if ($this->getRecord()->type === 'foundation') {
            $data['settings'] = $this->foundationSettingsForForm($data);
        }

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        [$this->cachedSlides, $data] = $this->extractSlides($data);

        [$translations, $data] = $this->extractTranslations($data);

        $this->cachedTranslations = $translations;

        $type = $data['type'] ?? $this->record?->type;

        if ($type === 'foundation') {
            $formSettings = data_get($this->form->getState(), 'settings');
            if (is_array($formSettings)) {
                $data['settings'] = $formSettings;
            }
        }

        $data = $this->prepareFoundationSettings(
            $this->prepareAboutSnippetSettings($data),
        );

        if (in_array($type, ['foundation', 'about_snippet'], true)) {
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

        if ($this->record->type === 'foundation') {
            $this->persistFoundationContent();

            return;
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

    protected function persistFoundationContent(): void
    {
        $rawSettings = data_get($this->form->getState(), 'settings');

        if (! is_array($rawSettings)) {
            $rawSettings = $this->cachedStructuredSettings ?? $this->record->settings ?? [];
        }

        $settings = FoundationSection::normalizeSettings(
            is_array($rawSettings) ? $rawSettings : [],
        );

        $this->record->update(['settings' => $settings]);
        $this->record->refresh();

        foreach (['ar', 'en'] as $locale) {
            HomeSectionTranslation::query()->updateOrCreate(
                ['home_section_id' => $this->record->id, 'locale' => $locale],
                [
                    'content' => FoundationSection::encodePayload(
                        FoundationSection::localePayloadFromSettings($settings, $locale),
                    ),
                ],
            );
        }

        app(HomePageService::class)->clearCache();
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
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function foundationSettingsForForm(array $data): array
    {
        return FoundationSection::settingsForAdminForm($this->getRecord());
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
