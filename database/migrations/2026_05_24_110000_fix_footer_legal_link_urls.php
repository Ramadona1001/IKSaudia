<?php

use App\Models\SiteSetting;
use App\Services\SettingsService;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $setting = SiteSetting::query()->where('key', 'footer.legal_links')->first();

        if (! $setting) {
            return;
        }

        $links = json_decode((string) $setting->value, true) ?? [];

        foreach ($links as &$link) {
            $url = trim((string) ($link['url'] ?? ''));

            if ($url !== '' && $url !== '#') {
                continue;
            }

            $label = strtolower((string) ($link['label_en'] ?? $link['label_ar'] ?? ''));

            if (str_contains($label, 'privacy') || str_contains($label, 'خصوص')) {
                $link['url'] = 'privacy-policy';
            } elseif (str_contains($label, 'term') || str_contains($label, 'شرط')) {
                $link['url'] = 'terms-of-use';
            }
        }

        unset($link);

        $setting->update([
            'value' => json_encode($links, JSON_UNESCAPED_UNICODE),
        ]);

        app(SettingsService::class)->clearCache();
    }

    public function down(): void
    {
        // Non-destructive.
    }
};
