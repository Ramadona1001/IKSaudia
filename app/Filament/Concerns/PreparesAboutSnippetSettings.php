<?php

namespace App\Filament\Concerns;

use App\Support\AboutSectionStats;

trait PreparesAboutSnippetSettings
{
    use ResolvesHomeSectionRecordType;

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

        $data['settings'] = [
            'stats' => $settings['stats'],
            'years_badge' => $settings['years_badge'],
        ];

        return $data;
    }
}
