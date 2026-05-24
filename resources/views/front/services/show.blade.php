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

    <section class="section-pad bg-dark1">
        <div class="container">
            <div class="row g-5">

                {{-- Main Content --}}
                <div class="col-lg-8" data-aos="fade-right">
                    @if ($service->featured_image_url)
                        <div style="height:380px;border-radius:var(--radius-xl);overflow:hidden;margin-bottom:40px;background-image:url('{{ $service->featured_image_url }}');background-size:cover;background-position:center;"></div>
                    @else
                        <div style="height:380px;border-radius:var(--radius-xl);overflow:hidden;margin-bottom:40px;background:linear-gradient(135deg,#0d2137 0%,#1a3a5c 40%,#0a1020 100%);display:flex;align-items:center;justify-content:center;position:relative;">
                            <i class="bi {{ $service->icon ?: 'bi-gear-wide-connected' }}" style="font-size:10rem;color:rgba(0,168,232,0.15);" aria-hidden="true"></i>
                            <div style="position:absolute;bottom:24px;inset-inline-start:24px;background:var(--grad-gold);border-radius:var(--radius);padding:10px 20px;font-size:0.8rem;font-weight:700;color:var(--c-dark);letter-spacing:1px;">{{ __('common.featured') }}</div>
                        </div>
                    @endif

                    <h2 class="section-title mb-3">{{ __('front.services.about_service') }}</h2>
                    @if ($summary)
                        <p class="section-desc mb-4">{{ $summary }}</p>
                    @endif
                    @if ($body)
                        <div class="prose-light" style="color:var(--c-muted);line-height:1.8;">{!! safe_html($body) !!}</div>
                    @endif
                </div>

                {{-- Sidebar --}}
                <div class="col-lg-4" data-aos="fade-left">
                    {{-- Quote CTA --}}
                    <div style="background:rgba(255,255,255,0.025);border:1px solid rgba(255,255,255,0.06);border-radius:var(--radius-lg);padding:28px;margin-bottom:24px;border-top:3px solid var(--c-gold);">
                        <h4 style="font-size:0.75rem;font-weight:700;letter-spacing:3px;text-transform:uppercase;color:var(--c-gold);margin-bottom:20px;">{{ __('front.services.get_quote_title') }}</h4>
                        <p style="font-size:0.86rem;color:var(--c-muted);margin-bottom:20px;">{{ __('front.services.get_quote_desc') }}</p>
                        <a href="{{ route('contact', $locale) }}" class="btn-gold" style="width:100%;justify-content:center;">
                            <i class="bi bi-envelope-fill" aria-hidden="true"></i>
                            <span>{{ __('buttons.request_quote') }}</span>
                        </a>
                    </div>

                    {{-- Other Services --}}
                    @if (isset($related) && $related->isNotEmpty())
                        <div style="background:rgba(255,255,255,0.025);border:1px solid rgba(255,255,255,0.06);border-radius:var(--radius-lg);padding:28px;margin-bottom:24px;">
                            <h4 style="font-size:0.75rem;font-weight:700;letter-spacing:3px;text-transform:uppercase;color:var(--c-gold);margin-bottom:20px;">{{ __('front.services.other_services') }}</h4>
                            <ul style="list-style:none;padding:0;">
                                @foreach ($related as $r)
                                    @php $rt = $r->translate($locale); @endphp
                                    @if ($rt)
                                        <li style="border-bottom:1px solid rgba(255,255,255,0.05);padding:10px 0;">
                                            <a href="{{ route('services.show', [$locale, $rt->slug]) }}"
                                               style="display:flex;align-items:center;gap:10px;font-size:0.86rem;color:var(--c-muted);transition:color 0.2s;">
                                                <i class="bi {{ $r->icon ?: 'bi-gear-fill' }}" style="color:var(--c-gold);" aria-hidden="true"></i>
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
