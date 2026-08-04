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
        $settings = data_get($this->form->getState(), 'settings');

        return is_array($settings) ? $settings : [];
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
            is_array($data['settings'] ?? null) ? $data['settings'] : [],
        );

        return $data;
    }
}
