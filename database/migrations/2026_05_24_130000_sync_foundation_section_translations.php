<?php

use App\Models\HomeSection;
use App\Models\HomeSectionTranslation;
use App\Support\FoundationSection;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $section = HomeSection::query()
            ->where('key', 'foundation')
            ->orWhere('type', 'foundation')
            ->first();

        if (! $section) {
            return;
        }

        if ($section->type !== 'foundation') {
            $section->update([
                'key' => 'foundation',
                'type' => 'foundation',
            ]);
        }

        $settings = FoundationSection::normalizeSettings(
            is_array($section->settings) ? $section->settings : [],
        );

        $section->update(['settings' => $settings]);

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
    }

    public function down(): void
    {
        // Non-destructive.
    }
};
