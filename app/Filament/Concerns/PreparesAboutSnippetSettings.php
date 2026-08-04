<?php

namespace App\Filament\Concerns;

use App\Support\AboutSectionStats;

trait PreparesAboutSnippetSettings
{
    use ResolvesHomeSectionRecordType;

    /**
     * @return array<string, mixed>
     */
    protected function aboutSnippetSettingsFromForm(): array
    {
        $state = $this->form->getState();

        if (is_array($state['settings'] ?? null)) {
            return $state['settings'];
        }

        if (property_exists($this, 'data') && is_array($this->data['settings'] ?? null)) {
            return $this->data['settings'];
        }

        return [];
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function prepareAboutSnippetSettings(array $data): array
    {
        $type = $data['type'] ?? $this->resolveRecordType();

        if ($type !== 'about_snippet') {
            return $data;
        }

        $data['settings'] = AboutSectionStats::sanitizeSettings(
            $this->resolveAboutSnippetSettingsPayload($data),
        );

        return $data;
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function resolveAboutSnippetSettingsPayload(array $data): array
    {
        $candidates = [
            $this->aboutSnippetSettingsFromForm(),
            is_array($data['settings'] ?? null) ? $data['settings'] : [],
        ];

        if (property_exists($this, 'data') && is_array($this->data['settings'] ?? null)) {
            $candidates[] = $this->data['settings'];
        }

        if (is_array($this->record?->settings)) {
            $candidates[] = $this->record->settings;
        }

        foreach ($candidates as $candidate) {
            if ($this->aboutSnippetSettingsLookFilled($candidate)) {
                return $candidate;
            }
        }

        return $candidates[0] ?: [];
    }

    /**
     * @param  array<string, mixed>  $settings
     */
    protected function aboutSnippetSettingsLookFilled(array $settings): bool
    {
        $normalized = AboutSectionStats::normalizeSettings($settings);

        foreach (['ar', 'en'] as $locale) {
            if (count($normalized['stats'][$locale] ?? []) >= 4) {
                return true;
            }
        }

        return false;
    }
}
