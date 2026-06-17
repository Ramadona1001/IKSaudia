<?php

use App\Models\SiteSetting;
use App\Services\SettingsService;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    private const LINKEDIN_URL = 'https://www.linkedin.com/company/iksforindustries';

    public function up(): void
    {
        $setting = SiteSetting::query()->where('key', 'social.links')->first();

        if (! $setting) {
            return;
        }

        $links = json_decode((string) $setting->value, true) ?? [];
        $found = false;

        foreach ($links as &$link) {
            if (($link['platform'] ?? '') !== 'linkedin') {
                continue;
            }

            if (empty($link['url'])) {
                $link['url'] = self::LINKEDIN_URL;
            }

            $link['enabled'] = true;
            $link['label'] = $link['label'] ?? 'LinkedIn';
            $found = true;
        }

        unset($link);

        if (! $found) {
            $links[] = [
                'platform' => 'linkedin',
                'url' => self::LINKEDIN_URL,
                'enabled' => true,
                'label' => 'LinkedIn',
            ];
        }

        $setting->update([
            'value' => json_encode($links, JSON_UNESCAPED_UNICODE),
        ]);

        app(SettingsService::class)->clearCache();
    }

    public function down(): void
    {
        // Non-destructive: leave configured URLs in place.
    }
};
