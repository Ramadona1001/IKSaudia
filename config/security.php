<?php

return [

    'csp' => [
        'enabled' => env('SECURITY_CSP_ENABLED', true),
        'report_only' => env('SECURITY_CSP_REPORT_ONLY', true),
        'report_uri' => env('SECURITY_CSP_REPORT_URI'),

        'public' => [
            'default-src' => ["'self'"],
            'base-uri' => ["'self'"],
            'form-action' => ["'self'"],
            'frame-ancestors' => ["'self'"],
            'object-src' => ["'none'"],
            'script-src' => ["'self'", 'https://challenges.cloudflare.com', 'https://cdn.jsdelivr.net'],
            'style-src' => ["'self'", "'unsafe-inline'", 'https://cdn.jsdelivr.net'],
            'img-src' => ["'self'", 'data:', 'https:'],
            'font-src' => ["'self'", 'https://cdn.jsdelivr.net'],
            'connect-src' => ["'self'"],
            'frame-src' => ["'self'", 'https://challenges.cloudflare.com'],
            'upgrade-insecure-requests' => [],
        ],

        'admin' => [
            'default-src' => ["'self'"],
            'base-uri' => ["'self'"],
            'form-action' => ["'self'"],
            'frame-ancestors' => ["'self'"],
            'object-src' => ["'none'"],
            'script-src' => ["'self'", "'unsafe-inline'", "'unsafe-eval'"],
            'style-src' => ["'self'", "'unsafe-inline'"],
            'img-src' => ["'self'", 'data:', 'blob:', 'https:'],
            'font-src' => ["'self'", 'data:'],
            'connect-src' => ["'self'"],
        ],
    ],

    'headers' => [
        'x_frame_options' => 'SAMEORIGIN',
        'x_content_type_options' => 'nosniff',
        'referrer_policy' => 'strict-origin-when-cross-origin',
        'permissions_policy' => 'camera=(), microphone=(), geolocation=(), payment=()',
        'cross_origin_opener_policy' => 'same-origin',
        'cross_origin_resource_policy' => 'same-site',
    ],

    'hsts' => [
        'enabled' => env('SECURITY_HSTS_ENABLED', true),
        'max_age' => 31536000,
        'include_subdomains' => true,
        'preload' => env('SECURITY_HSTS_PRELOAD', false),
    ],

    'admin' => [
        'ip_allowlist_enabled' => env('ADMIN_IP_ALLOWLIST_ENABLED', false),
        'ip_allowlist' => array_values(array_filter(array_map(
            'trim',
            explode(',', (string) env('ADMIN_IP_ALLOWLIST', ''))
        ))),
    ],

    'password' => [
        'min_length' => (int) env('SECURITY_PASSWORD_MIN_LENGTH', 12),
    ],

    'lockout' => [
        'max_attempts' => (int) env('SECURITY_LOCKOUT_MAX_ATTEMPTS', 5),
        'decay_minutes' => (int) env('SECURITY_LOCKOUT_DECAY_MINUTES', 15),
    ],

    'turnstile' => [
        'enabled' => env('TURNSTILE_ENABLED', false),
        'site_key' => env('TURNSTILE_SITE_KEY'),
        'secret_key' => env('TURNSTILE_SECRET_KEY'),
    ],

    'contact' => [
        'min_submit_seconds' => (int) env('CONTACT_MIN_SUBMIT_SECONDS', 3),
    ],

    'uploads' => [
        'max_image_kb' => (int) env('UPLOAD_MAX_IMAGE_KB', 5120),
        'allowed_mimes' => ['image/jpeg', 'image/png', 'image/webp', 'image/gif'],
        'allowed_extensions' => ['jpg', 'jpeg', 'png', 'webp', 'gif'],
        'strip_exif' => env('UPLOAD_STRIP_EXIF', true),
        'virus_scan_enabled' => env('UPLOAD_VIRUS_SCAN_ENABLED', false),
    ],

    'http' => [
        'allowed_hosts' => array_values(array_filter(array_map(
            'trim',
            explode(',', (string) env('HTTP_ALLOWED_HOSTS', 'www.iksaudi.com,iksaudi.com'))
        ))),
    ],

    'two_factor' => [
        'enabled' => env('ADMIN_2FA_ENABLED', false),
        'required' => env('ADMIN_2FA_REQUIRED', false),
    ],

    'rate_limits' => [
        'contact_per_minute' => (int) env('RATE_LIMIT_CONTACT', 5),
        'contact_per_hour' => (int) env('RATE_LIMIT_CONTACT_HOUR', 20),
        'admin_login_per_minute' => (int) env('RATE_LIMIT_ADMIN_LOGIN', 5),
    ],

];
