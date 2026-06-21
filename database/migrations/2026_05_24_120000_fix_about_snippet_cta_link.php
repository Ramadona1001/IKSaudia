<?php

use App\Models\HomeSection;
use App\Models\HomeSectionTranslation;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $section = HomeSection::query()->where('key', 'about_snippet')->first();

        if (! $section) {
            return;
        }

        $updates = [
            'en' => [
                'cta_label' => 'Learn More',
                'cta_url' => '/en/about-us',
            ],
            'ar' => [
                'cta_label' => 'اعرف المزيد',
                'cta_url' => '/ar/about-us',
            ],
        ];

        foreach ($updates as $locale => $data) {
            HomeSectionTranslation::query()
                ->where('home_section_id', $section->id)
                ->where('locale', $locale)
                ->update($data);
        }
    }

    public function down(): void
    {
        // Non-destructive.
    }
};
