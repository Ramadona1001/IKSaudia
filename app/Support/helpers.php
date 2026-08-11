<?php

use App\Services\Security\HtmlSanitizer;

if (! function_exists('safe_html')) {
    function safe_html(?string $html): string
    {
        return app(HtmlSanitizer::class)->clean($html) ?? '';
    }
}

if (! function_exists('asset_version')) {
    /**
     * Public asset URL with a filemtime cache-busting query string (?v=).
     */
    function asset_version(string $path): string
    {
        $normalized = ltrim($path, '/');
        $fullPath = public_path($normalized);
        $version = is_file($fullPath) ? (string) filemtime($fullPath) : (string) config('app.asset_version', '1');

        return asset($normalized).'?v='.$version;
    }
}
