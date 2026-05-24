@extends('front.layouts.app')

@section('title', __('front.error_404.title'))
@section('meta_description', __('front.error_404.subtitle'))

@push('seo')
    <meta name="robots" content="noindex, nofollow">
@endpush

@section('content')

    <section style="min-height:90vh;display:flex;align-items:center;padding:140px 0 80px;position:relative;background:linear-gradient(135deg,var(--c-dark1) 0%,var(--c-dark2) 100%);">
        <div class="container">
            <div class="row align-items-center g-5">

                <div class="col-lg-7" data-aos="fade-right">
                    <div style="font-size:9rem;font-weight:900;line-height:1;background:var(--grad-gold);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;letter-spacing:-4px;margin-bottom:20px;">404</div>
                    <h1 style="font-size:clamp(1.6rem,3.5vw,2.4rem);font-weight:800;color:var(--c-white);margin-bottom:20px;line-height:1.2;">
                        {{ __('front.error_404.title') }}
                    </h1>
                    <p style="font-size:1.05rem;color:var(--c-muted);margin-bottom:36px;line-height:1.7;max-width:580px;">
                        {{ __('front.error_404.subtitle') }}
                    </p>

                    <div style="display:flex;gap:14px;flex-wrap:wrap;">
                        <a href="{{ url('/'.app()->getLocale()) }}" class="btn-gold">
                            <i class="bi bi-house-fill" aria-hidden="true"></i>
                            <span>{{ __('buttons.go_home') }}</span>
                        </a>
                        <a href="{{ route('contact', app()->getLocale()) }}" class="btn-outline-gold">
                            <i class="bi bi-headset" aria-hidden="true"></i>
                            <span>{{ __('buttons.contact_support') }}</span>
                        </a>
                    </div>

                    {{-- Helpful links --}}
                    <div style="margin-top:50px;">
                        <div style="font-size:0.78rem;font-weight:700;letter-spacing:3px;text-transform:uppercase;color:var(--c-gold);margin-bottom:16px;">{{ __('front.error_404.helpful_links') }}</div>
                        <div style="display:flex;gap:10px;flex-wrap:wrap;">
                            <a href="{{ route('services.index', app()->getLocale()) }}" class="btn-outline-gold" style="font-size:0.84rem;padding:8px 16px;">{{ __('navigation.services') }}</a>
                            <a href="{{ route('industries.index', app()->getLocale()) }}" class="btn-outline-gold" style="font-size:0.84rem;padding:8px 16px;">{{ __('navigation.industries') }}</a>
                            <a href="{{ route('partners', app()->getLocale()) }}" class="btn-outline-gold" style="font-size:0.84rem;padding:8px 16px;">{{ __('navigation.partners') }}</a>
                            <a href="{{ route('about', app()->getLocale()) }}" class="btn-outline-gold" style="font-size:0.84rem;padding:8px 16px;">{{ __('navigation.about') }}</a>
                            <a href="{{ route('faq', app()->getLocale()) }}" class="btn-outline-gold" style="font-size:0.84rem;padding:8px 16px;">{{ __('navigation.faq') }}</a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5" data-aos="fade-left">
                    <div style="position:relative;width:100%;aspect-ratio:1;max-width:420px;margin-inline:auto;display:flex;align-items:center;justify-content:center;">
                        <div style="position:absolute;inset:0;background:radial-gradient(circle,rgba(0,168,232,0.12) 0%,transparent 65%);"></div>
                        <i class="bi bi-compass" style="font-size:14rem;color:rgba(0,168,232,0.18);position:relative;z-index:1;" aria-hidden="true"></i>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
