@props(['seo' => null, 'fallbackTitle' => null, 'fallbackDescription' => null])

@php
    $title = $seo?->meta_title ?? $fallbackTitle ?? setting('seo.default_meta_title');
    $description = $seo?->meta_description ?? $fallbackDescription ?? setting('seo.default_meta_description');
    $ogImage = $seo?->og_image ?? setting_url('seo.og_image') ?? setting_url('general.seo_default_image');
@endphp

@if ($title)
    @section('title', $title)
@endif

@push('head')
    @if ($description)
        <meta name="description" content="{{ $description }}">
    @endif
    @if ($seo?->meta_keywords)
        <meta name="keywords" content="{{ $seo->meta_keywords }}">
    @endif
    @if ($seo?->robots)
        <meta name="robots" content="{{ $seo->robots }}">
    @endif
    @if ($seo?->canonical_url)
        <link rel="canonical" href="{{ $seo->canonical_url }}">
    @endif
    <meta property="og:title" content="{{ $seo?->og_title ?? $title }}">
    @if ($seo?->og_description ?? $description)
        <meta property="og:description" content="{{ $seo?->og_description ?? $description }}">
    @endif
    @if ($ogImage)
        <meta property="og:image" content="{{ $ogImage }}">
    @endif
    @if (setting('seo.default_keywords'))
        <meta name="keywords" content="{{ setting('seo.default_keywords') }}">
    @endif
    <meta property="og:type" content="website">
    <meta property="og:locale" content="{{ __('common.og_locale') }}">
@endpush
