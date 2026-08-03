@extends('front.layouts.app')

@php
    $locale = app()->getLocale();
    $title = $translation?->title ?? '—';
    $summary = $translation?->summary ?? '';
    $body = $translation?->body ?? '';
@endphp

@section('title', $title)
@section('meta_description', $seo?->meta_description ?: ($summary ?: __('front.industries.subtitle')))

@push('seo')
    @if ($seo?->meta_keywords)
        <meta name="keywords" content="{{ $seo->meta_keywords }}">
    @endif
@endpush

@section('content')

    <x-front.page-hero
        :tag="__('navigation.industries')"
        :icon="$industry->icon ?: 'bi-grid-3x3-gap-fill'"
        :title="$title"
        :subtitle="$summary"
    />

    <x-front.breadcrumb :items="[
        ['section' => 'industries', 'url' => route('industries.index', $locale)],
        ['label' => $title],
    ]" />

    <section class="section-pad bg-dark1">
        <div class="container">
            <div class="row g-5">

                <div class="col-lg-8" data-aos="fade-right">
                    @if ($industry->featured_image_url)
                        <div style="height:360px;border-radius:var(--radius-xl);overflow:hidden;margin-bottom:40px;background-image:url('{{ $industry->featured_image_url }}');background-size:cover;background-position:center;"></div>
                    @else
                        <div style="height:360px;border-radius:var(--radius-xl);overflow:hidden;margin-bottom:40px;background:linear-gradient(135deg,#0d2137 0%,#1a3a5c 40%,#0a1020 100%);display:flex;align-items:center;justify-content:center;position:relative;">
                            <i class="{{ \App\Support\BootstrapIcon::classes($industry->icon, 'bi-grid-3x3-gap-fill') }}" style="font-size:10rem;color:rgba(0,168,232,0.15);" aria-hidden="true"></i>
                        </div>
                    @endif

                    <h2 class="section-title mb-3">{{ __('front.industries.about_industry') }}</h2>
                    @if ($summary)
                        <p class="section-desc mb-4">{{ $summary }}</p>
                    @endif
                    @if ($body)
                        <div class="prose-light" style="color:var(--c-muted);line-height:1.8;">{!! safe_html($body) !!}</div>
                    @endif

                    @if (isset($industry->services) && $industry->services->isNotEmpty())
                        <h3 style="font-size:1.2rem;font-weight:700;color:var(--c-white);margin:36px 0 20px;">{{ __('navigation.services') }}</h3>
                        <div class="row g-3">
                            @foreach ($industry->services as $svc)
                                @php $st = $svc->translate($locale); @endphp
                                @if ($st)
                                    <div class="col-md-6">
                                        <a href="{{ route('services.show', [$locale, $st->slug]) }}"
                                           style="display:flex;gap:10px;align-items:flex-start;padding:14px;border:1px solid rgba(255,255,255,0.06);border-radius:var(--radius);font-size:0.9rem;color:var(--c-muted);text-decoration:none;transition:all 0.2s;">
                                            <i class="{{ \App\Support\BootstrapIcon::classes($svc->icon, 'bi-check-circle-fill') }}" style="color:var(--c-gold);margin-top:2px;flex-shrink:0;" aria-hidden="true"></i>
                                            <span>{{ $st->title }}</span>
                                        </a>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @endif

                    {{-- Related industries --}}
                    @if (isset($related) && $related->isNotEmpty())
                        <h3 style="font-size:1.2rem;font-weight:700;color:var(--c-white);margin:36px 0 20px;">{{ __('front.industries.other_industries') }}</h3>
                        <div style="display:flex;gap:12px;flex-wrap:wrap;">
                            @foreach ($related as $r)
                                @php $rt = $r->translate($locale); @endphp
                                @if ($rt)
                                    <a href="{{ route('industries.show', [$locale, $rt->slug]) }}"
                                       style="display:flex;align-items:center;gap:8px;background:rgba(255,255,255,0.025);border:1px solid rgba(255,255,255,0.06);border-radius:var(--radius-pill);padding:10px 18px;font-size:0.82rem;color:var(--c-muted);text-decoration:none;transition:all 0.2s;">
                                        <i class="{{ \App\Support\BootstrapIcon::classes($r->icon, 'bi-grid') }}" style="color:var(--c-gold);" aria-hidden="true"></i>
                                        {{ $rt->title }}
                                    </a>
                                @endif
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Sidebar --}}
                <div class="col-lg-4" data-aos="fade-left">
                    <div style="background:rgba(201,162,39,0.06);border:1px solid rgba(201,162,39,0.15);border-radius:var(--radius-xl);padding:32px;margin-bottom:24px;">
                        <div style="font-size:0.75rem;font-weight:700;letter-spacing:3px;text-transform:uppercase;color:var(--c-gold);margin-bottom:16px;">{{ __('front.services.get_quote_title') }}</div>
                        <p style="font-size:0.86rem;color:var(--c-muted);margin-bottom:20px;line-height:1.7;">{{ __('front.services.get_quote_desc') }}</p>
                        <a href="{{ route('contact', $locale) }}" class="btn-gold" style="width:100%;justify-content:center;">
                            <i class="bi bi-envelope-fill" aria-hidden="true"></i>
                            <span>{{ __('buttons.request_quote') }}</span>
                        </a>
                    </div>

                    {{-- Direct Contact --}}
                    @php
                        $phones = $siteSettings?->phones() ?? [];
                        $emails = $siteSettings?->emails() ?? [];
                    @endphp
                    @if (count($phones) || count($emails))
                        <div style="background:rgba(255,255,255,0.025);border:1px solid rgba(255,255,255,0.06);border-radius:var(--radius-lg);padding:28px;">
                            <div style="font-size:0.75rem;font-weight:700;letter-spacing:3px;text-transform:uppercase;color:var(--c-gold);margin-bottom:20px;">{{ __('front.industries.direct_contact') }}</div>
                            @foreach (array_slice($phones, 0, 1) as $phone)
                                <div style="display:flex;align-items:center;gap:12px;margin-bottom:12px;">
                                    <div style="width:38px;height:38px;background:rgba(201,162,39,0.1);border:1px solid rgba(201,162,39,0.2);border-radius:var(--radius);display:flex;align-items:center;justify-content:center;color:var(--c-gold);flex-shrink:0;">
                                        <i class="bi bi-telephone-fill" aria-hidden="true"></i>
                                    </div>
                                    <a href="tel:{{ preg_replace('/\s+/', '', $phone['number'] ?? '') }}" style="font-size:0.86rem;color:var(--c-muted);">{{ $phone['number'] }}</a>
                                </div>
                            @endforeach
                            @foreach (array_slice($emails, 0, 1) as $email)
                                <div style="display:flex;align-items:center;gap:12px;">
                                    <div style="width:38px;height:38px;background:rgba(201,162,39,0.1);border:1px solid rgba(201,162,39,0.2);border-radius:var(--radius);display:flex;align-items:center;justify-content:center;color:var(--c-gold);flex-shrink:0;">
                                        <i class="bi bi-envelope-fill" aria-hidden="true"></i>
                                    </div>
                                    <a href="mailto:{{ $email['address'] ?? '' }}" style="font-size:0.86rem;color:var(--c-muted);">{{ $email['address'] }}</a>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </section>

@endsection
