@extends('front.layouts.app')

@php
    $locale = app()->getLocale();
    $title = $translation?->title ?? '—';
    $summary = $translation?->summary ?? '';
    $body = $translation?->body ?? '';
@endphp

@section('title', $title)
@section('meta_description', $seo?->meta_description ?: ($summary ?: __('front.services.subtitle')))

@push('seo')
    @if ($seo?->meta_keywords)
        <meta name="keywords" content="{{ $seo->meta_keywords }}">
    @endif
@endpush

@section('content')

    <x-front.page-hero
        :tag="__('navigation.services')"
        icon="bi-gear-fill"
        :title="$title"
        :subtitle="$summary"
    />

    <x-front.breadcrumb :items="[
        ['label' => __('navigation.services'), 'url' => route('services.index', $locale)],
        ['label' => $title],
    ]" />

    <section class="section-pad service-show-section">
        <div class="container">
            <div class="row g-5">

                {{-- Main Content --}}
                <div class="col-lg-8" data-aos="fade-right">
                    @if ($service->featured_image_url)
                        <div class="service-show-media" style="background-image:url('{{ $service->featured_image_url }}');"></div>
                    @else
                        <div class="service-show-media service-show-media--placeholder">
                            <i class="bi {{ $service->icon ?: 'bi-gear-wide-connected' }}" aria-hidden="true"></i>
                            <div class="service-show-featured-badge">{{ __('common.featured') }}</div>
                        </div>
                    @endif

                    <h2 class="section-title mb-3">{{ __('front.services.about_service') }}</h2>
                    @if ($summary)
                        <p class="section-desc mb-4">{{ $summary }}</p>
                    @endif
                    @if ($body)
                        <div class="service-show-body">{!! safe_html($body) !!}</div>
                    @endif
                </div>

                {{-- Sidebar --}}
                <div class="col-lg-4" data-aos="fade-left">
                    <div class="service-show-panel service-show-panel--quote">
                        <h4 class="service-show-panel-title">{{ __('front.services.get_quote_title') }}</h4>
                        <p class="service-show-panel-desc">{{ __('front.services.get_quote_desc') }}</p>
                        <a href="{{ route('contact', $locale) }}" class="btn-gold service-show-quote-btn">
                            <i class="bi bi-envelope-fill" aria-hidden="true"></i>
                            <span>{{ __('buttons.request_quote') }}</span>
                        </a>
                    </div>

                    @if (isset($related) && $related->isNotEmpty())
                        <div class="service-show-panel">
                            <h4 class="service-show-panel-title">{{ __('front.services.other_services') }}</h4>
                            <ul class="service-show-related">
                                @foreach ($related as $r)
                                    @php $rt = $r->translate($locale); @endphp
                                    @if ($rt)
                                        <li>
                                            <a href="{{ route('services.show', [$locale, $rt->slug]) }}">
                                                <i class="bi {{ $r->icon ?: 'bi-gear-fill' }}" aria-hidden="true"></i>
                                                <span>{{ $rt->title }}</span>
                                            </a>
                                        </li>
                                    @endif
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

@endsection
