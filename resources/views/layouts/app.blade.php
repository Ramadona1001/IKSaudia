@php
    $locale = app()->getLocale();
    $dir = request()->attributes->get('text_direction', str_starts_with(app()->getLocale(), 'ar') ? 'rtl' : 'ltr');
    $brandPrimary = setting('branding.primary_color', '#0c1f38') ?: '#0c1f38';
    $brandSecondary = setting('branding.secondary_color', '#1a3d66') ?: '#1a3d66';
    $brandAccent = setting('branding.accent_color', '#c8922a') ?: '#c8922a';
    $defaultRobots = setting('seo.robots', 'index, follow');
    $loaderLogo = setting_url('branding.loading_logo') ?? setting_url('general.logo');
@endphp
<!DOCTYPE html>
<html lang="{{ $locale }}" dir="{{ $dir }}" class="scroll-smooth" style="--brand-primary: {{ $brandPrimary }}; --brand-secondary: {{ $brandSecondary }}; --brand-accent: {{ $brandAccent }};">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="{{ $brandPrimary }}">
    <meta name="robots" content="{{ $defaultRobots }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', setting('seo.default_meta_title') ?: setting('general.site_name') ?: config('app.name'))</title>
    @if ($favicon = setting_url('general.favicon'))
        <link rel="icon" href="{{ $favicon }}" type="image/png">
    @endif
    <link rel="preconnect" href="https://fonts.bunny.net" crossorigin>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        :root {
            --color-navy-950: color-mix(in srgb, {{ $brandPrimary }} 92%, #000 8%);
            --color-navy-900: color-mix(in srgb, {{ $brandPrimary }} 85%, #000 15%);
            --color-navy-800: {{ $brandPrimary }};
            --color-navy-700: color-mix(in srgb, {{ $brandPrimary }} 40%, {{ $brandSecondary }} 60%);
            --color-navy-600: {{ $brandSecondary }};
            --color-accent: {{ $brandAccent }};
            --color-accent-light: color-mix(in srgb, {{ $brandAccent }} 70%, #fff 30%);
            --color-accent-dark: color-mix(in srgb, {{ $brandAccent }} 75%, #000 25%);
        }
    </style>
    @include('partials.site-analytics')
    @stack('head')
</head>
<body class="min-h-screen flex flex-col bg-navy-950 text-steel-100 antialiased">
    @if (setting('seo.google_tag_manager_id'))
        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ setting('seo.google_tag_manager_id') }}" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    @endif
    <div x-data="pageLoader" :class="done && 'is-done'" class="page-loader" aria-hidden="true" x-show="!done" x-transition.opacity.duration.500ms>
        @if ($loaderLogo)
            <img src="{{ $loaderLogo }}" alt="" class="h-14 w-auto mb-6 opacity-90" width="120" height="56" />
        @else
            <div class="loader-ring" role="presentation"></div>
        @endif
        <p class="text-overline text-steel-400 animate-pulse-soft">{{ setting('general.site_name') ?: __('common.app_name') }}</p>
    </div>

    <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:start-4 focus:z-[100] focus:rounded-lg focus:bg-accent focus:px-4 focus:py-2 focus:text-navy-950">
        {{ __('common.skip_to_content') }}
    </a>

    <x-layout.site-header :featured-services="$featuredServices ?? collect()" />

    <main id="main-content" class="flex-1" x-data="scrollReveal">
        @yield('content')
    </main>

    <x-layout.site-footer />
</body>
</html>
