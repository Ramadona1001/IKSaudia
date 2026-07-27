<?php

namespace App\Services;

use App\Models\HomeSection;
use App\Models\HomeSectionTranslation;
use App\Support\FoundationSection;

class FoundationSectionService
{
    public function section(): HomeSection
    {
        return HomeSection::query()->firstOrCreate(
            ['key' => 'foundation'],
            [
                'type' => 'foundation',
                'sort_order' => 3,
                'is_active' => true,
                'settings' => [],
            ],
        );
    }

    /**
     * @return array{is_active: bool, settings: array<string, mixed>}
     */
    public function formState(?HomeSection $section = null): array
    {
        $section ??= $this->section();
        $section->load('translations');

        return [
            'is_active' => (bool) $section->is_active,
            'settings' => FoundationSection::settingsForAdminForm($section),
        ];
    }

    /**
     * @param  array<string, mixed>  $settings
     */
    public function persist(HomeSection $section, array $settings, ?bool $isActive = null): void
    {
        $settings = FoundationSection::normalizeSettings($settings);

        $section->update([
            'settings' => $settings,
            'is_active' => $isActive ?? $section->is_active,
        ]);

        foreach (['ar', 'en'] as $locale) {
            HomeSectionTranslation::query()->updateOrCreate(
                ['home_section_id' => $section->id, 'locale' => $locale],
                [
                    'content' => FoundationSection::encodePayload(
                        FoundationSection::localePayloadFromSettings($settings, $locale),
                    ),
                ],
            );
        }

        app(HomePageService::class)->clearCache();
    }
}
