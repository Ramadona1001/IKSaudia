<?php

use App\Services\Security\HtmlSanitizer;

if (! function_exists('safe_html')) {
    function safe_html(?string $html): string
    {
        return app(HtmlSanitizer::class)->clean($html) ?? '';
    }
}
