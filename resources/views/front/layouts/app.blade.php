@php
    $locale = app()->getLocale();
    $dir = request()->attributes->get('text_direction', $locale === 'ar' ? 'rtl' : 'ltr');

    $brandPrimary = setting('branding.primary_color', '#060c1a') ?: '#060c1a';
    $brandSecondary = setting('branding.secondary_color', '#1a2d4a') ?: '#1a2d4a';
    $brandAccent = setting('branding.accent_color', '#c9a227') ?: '#c9a227';

    $defaultRobots = setting('seo.robots', 'index, follow');
    $defaultTitle = setting('seo.default_meta_title') ?: setting('general.site_name') ?: config('app.name');
    $defaultDescription = setting('seo.default_meta_description') ?: __('footer.tagline');
    $defaultOgImage = setting_url('seo.default_og_image') ?? setting_url('general.logo');

    $title = $title ?? trim((string) View::yieldContent('title'));
    $metaTitle = $title !== '' ? $title.' — '.($defaultTitle ?? '') : $defaultTitle;

    $metaDescription = $metaDescription ?? trim((string) View::yieldContent('meta_description'));
    if ($metaDescription === '') {
        $metaDescription = $defaultDescription;
    }

    $canonical = $canonical ?? url()->current();
    $ogImage = $ogImage ?? $defaultOgImage;
    $robots = $robots ?? $defaultRobots;
@endphp
<!DOCTYPE html>
<html lang="{{ $locale }}" dir="{{ $dir }}" style="--brand-primary: {{ $brandPrimary }}; --brand-secondary: {{ $brandSecondary }}; --brand-accent: {{ $brandAccent }};">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    {{-- SEO --}}
    <title>{{ $metaTitle }}</title>
    <meta name="description" content="{{ $metaDescription }}">
    <meta name="robots" content="{{ $robots }}">
    <meta name="theme-color" content="{{ $brandPrimary }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="canonical" href="{{ $canonical }}">

    @stack('seo')

    {{-- OpenGraph --}}
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:title" content="{{ $metaTitle }}">
    <meta property="og:description" content="{{ $metaDescription }}">
    <meta property="og:url" content="{{ $canonical }}">
    <meta property="og:site_name" content="{{ setting('general.site_name') ?: config('app.name') }}">
    <meta property="og:locale" content="{{ __('common.og_locale') }}">
    @if ($ogImage)
        <meta property="og:image" content="{{ $ogImage }}">
    @endif

    {{-- Twitter --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ $metaTitle }}">
    <meta name="twitter:description" content="{{ $metaDescription }}">
    @if ($ogImage)
        <meta name="twitter:image" content="{{ $ogImage }}">
    @endif

    {{-- Alternates for locale --}}
    @php $currentRouteName = request()->route()?->getName(); @endphp
    @if ($currentRouteName && Route::has($currentRouteName))
        @foreach (['en', 'ar'] as $altLocale)
            @php
                $altParams = in_array($currentRouteName, ['products.index', 'products.show'], true)
                    ? request()->route()?->parameters() ?? []
                    : array_merge(request()->route()?->parameters() ?? [], ['locale' => $altLocale]);
                $altHref = route($currentRouteName, $altParams).(in_array($currentRouteName, ['products.index', 'products.show'], true) ? '?locale='.$altLocale : '');
            @endphp
            <link rel="alternate" hreflang="{{ $altLocale }}" href="{{ $altHref }}">
        @endforeach
    @endif

    @if ($favicon = setting_url('general.favicon'))
        <link rel="icon" href="{{ $favicon }}">
    @else
        <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 40 40'%3E%3Cpolygon points='20,2 38,11 38,29 20,38 2,29 2,11' fill='%23c9a227'/%3E%3Ctext x='50%25' y='57%25' dominant-baseline='middle' text-anchor='middle' font-family='Arial' font-weight='900' font-size='14' fill='%23060c1a'%3EIK%3C/text%3E%3C/svg%3E">
    @endif

    @include('front.partials.styles')

    @stack('head')
</head>
<body dir="{{ $dir }}" data-server-locale class="@yield('body_class')">

    {{-- Skip link --}}
    <a href="#main-content" class="visually-hidden-focusable">
        {{-- {{ __('common.skip_to_content') }} --}}
    </a>

    @include('front.partials.loader')
    @include('front.partials.mobile-menu')
    @include('front.partials.header')
    @include('front.partials.search-modal')

    <main id="main-content">
        @yield('content')
    </main>

    @include('front.partials.footer')
    @include('front.partials.floating-buttons')
    @include('front.partials.scripts')
</body>
</html>
