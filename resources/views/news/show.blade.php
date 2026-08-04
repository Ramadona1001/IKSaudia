@extends('front.layouts.app')

@php
    $locale = app()->getLocale();
    $title = $translation?->title ?? '—';
    $excerpt = $translation?->excerpt ?? '';
    $body = $translation?->body ?? '';
@endphp

@section('title', $title)
@section('meta_description', $seo?->meta_description ?: ($excerpt ?: __('news.index.subtitle')))

@push('seo')
    @if ($seo?->meta_keywords)
        <meta name="keywords" content="{{ $seo->meta_keywords }}">
    @endif
@endpush

@section('content')
    <x-front.page-hero
        :tag="__('navigation.news')"
        icon="bi-newspaper"
        :title="$title"
        :subtitle="$excerpt"
    />

    <x-front.breadcrumb :items="[
        ['label' => __('navigation.news'), 'url' => route('news.index', $locale)],
        ['label' => $title],
    ]" />

    <section class="section-pad service-show-section">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-8" data-aos="fade-right">
                    @if ($post->featured_image_url)
                        <div class="service-show-media" style="background-image:url('{{ $post->featured_image_url }}');"></div>
                    @endif

                    @if ($excerpt)
                        <p class="lead mb-4">{{ $excerpt }}</p>
                    @endif

                    @if ($body)
                        <div class="cms-content">
                            {!! $body !!}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
