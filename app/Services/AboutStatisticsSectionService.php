<?php

namespace App\Services;

use App\Models\HomeSection;
use App\Support\AboutSectionStats;

class AboutStatisticsSectionService
{
    public function section(): HomeSection
    {
        return HomeSection::query()->firstOrCreate(
            ['key' => 'about_snippet'],
            [
                'type' => 'about_snippet',
                'sort_order' => 2,
                'is_active' => true,
                'settings' => AboutSectionStats::sanitizeSettings([]),
            ],
        );
    }

    /**
     * @return array{settings: array<string, mixed>}
     */
    public function formState(?HomeSection $section = null): array
    {
        $section ??= $this->section();
        $settings = AboutSectionStats::sanitizeSettings(
            is_array($section->settings) ? $section->settings : [],
        );

        return [
            'settings' => [
                'stats' => $settings['stats'],
                'years_badge' => $settings['years_badge'],
            ],
        ];
    }

    /**
     * @param  array<string, mixed>  $settings
     */
    public function persist(HomeSection $section, array $settings): void
    {
        $existing = is_array($section->settings) ? $section->settings : [];
        $statistics = AboutSectionStats::sanitizeSettings($settings);

        $section->update([
            'settings' => array_merge($existing, $statistics),
        ]);

        app(HomePageService::class)->clearCache();
    }
}
