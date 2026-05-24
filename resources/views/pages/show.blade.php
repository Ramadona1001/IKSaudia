@extends('front.layouts.app')

@php
    $locale = app()->getLocale();
    $title = $translation?->title ?? '—';
    $excerpt = $translation?->excerpt ?? '';
@endphp

@section('title', $title)
@section('meta_description', $seo?->meta_description ?: ($excerpt ?: setting('seo.default_meta_description')))

@push('seo')
    @if ($seo?->meta_keywords)
        <meta name="keywords" content="{{ $seo->meta_keywords }}">
    @endif
@endpush

@section('content')

    <x-front.page-hero
        :tag="setting('general.site_name') ?: config('app.name')"
        icon="bi-file-earmark-text-fill"
        :title="$title"
        :subtitle="$excerpt"
    />

    <x-front.breadcrumb :items="[['label' => $title]]" />

    <section class="section-pad bg-dark1">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-10 mx-auto" data-aos="fade-up">
                    @if ($page->featured_image_url)
                        <div style="height:380px;border-radius:var(--radius-xl);overflow:hidden;margin-bottom:40px;background-image:url('{{ $page->featured_image_url }}');background-size:cover;background-position:center;"></div>
                    @endif

                    @if ($translation?->body)
                        <div class="prose-light" style="color:var(--c-muted);line-height:1.85;font-size:1rem;">
                            {!! safe_html($translation->body) !!}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <x-front.cta-section :title="__('front.about.cta_title')" :description="__('front.about.cta_desc')">
        <a href="{{ route('contact', $locale) }}" class="btn-gold">
            <i class="bi bi-envelope-fill" aria-hidden="true"></i>
            <span>{{ __('buttons.contact_us') }}</span>
        </a>
    </x-front.cta-section>

@endsection
