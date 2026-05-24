@extends('front.layouts.app')

@section('title', __('navigation.about'))
@section('meta_description', __('front.about.subtitle'))

@section('content')

    <x-front.page-hero
        :tag="__('front.about.tag')"
        icon="bi-building"
        :title="__('front.about.title')"
        :highlight="__('front.about.highlight')"
        :subtitle="__('front.about.subtitle')"
    />

    <x-front.breadcrumb :items="[['label' => __('front.about.breadcrumb')]]" />

    {{-- Company Overview --}}
    <section class="section-pad bg-dark1">
        <div class="container">
            <div class="row g-5 align-items-center">

                <div class="col-lg-6" data-aos="fade-right">
                    <div class="about-visual">
                        <div class="about-img-main">
                            <div class="about-img-bg" style="background:linear-gradient(135deg,#0d2040 0%,#1a3a60 30%,#0a1528 100%);">
                                <div class="about-img-graphic" aria-hidden="true">
                                    <i class="bi bi-building-fill about-img-icon"></i>
                                </div>
                            </div>
                            <div class="about-badge">
                                <div class="about-badge-icon"><i class="bi bi-award-fill" aria-hidden="true"></i></div>
                                <div class="about-badge-text">
                                    <strong>{{ __('front.about.badge_year') }}</strong>
                                    <span>{{ __('front.about.badge_caption') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="about-img-float">
                            <div class="about-img-float-num" data-count="25" data-suffix="+">0+</div>
                            <div class="about-img-float-text">{{ __('front.about.years_strong') }}</div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6" data-aos="fade-left">
                    <div class="about-content">
                        <div class="section-eyebrow">{{ __('front.about.journey_eyebrow') }}</div>
                        <h2 class="section-title text-white">
                            <span class="accent">{{ setting('general.site_name') ?: __('common.app_name') }}</span><br>
                            <span>{{ __('front.about.journey_title') }}</span>
                        </h2>
                        <p class="section-desc mb-4">{{ __('front.about.journey_p1') }}</p>
                        <p class="section-desc">{{ __('front.about.journey_p2') }}</p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- Stats Section --}}
    <section class="section-pad bg-dark2">
        <div class="container">
            <div class="row g-4 text-center">
                <div class="col-lg-3 col-md-6">
                    <x-front.stat-counter :count="25" suffix="+" :label="__('front.home.about.stats.projects')" variant="gold" :delay="0" />
                </div>
                <div class="col-lg-3 col-md-6">
                    <x-front.stat-counter :count="2000" suffix="+" :label="__('front.home.about.stats.clients')" variant="blue" :delay="100" />
                </div>
                <div class="col-lg-3 col-md-6">
                    <x-front.stat-counter :count="500" suffix="+" :label="__('front.home.about.stats.projects')" variant="gold" :delay="200" />
                </div>
                <div class="col-lg-3 col-md-6">
                    <x-front.stat-counter :count="12" :label="__('front.home.about.stats.countries')" variant="blue" :delay="300" />
                </div>
            </div>
        </div>
    </section>

    {{-- Mission Vision Values --}}
    <section class="section-pad bg-dark1">
        <div class="container">
            <x-front.section-heading
                :eyebrow="__('front.about.foundation_eyebrow')"
                :title="__('front.about.foundation_title')"
                :highlight="__('front.about.foundation_highlight')"
                data-aos="fade-up"
            />

            <div class="row g-4">
                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="0">
                    <div class="service-card" style="border-top:3px solid var(--c-gold);">
                        <div class="service-body">
                            <div style="width:56px;height:56px;background:rgba(201,162,39,0.12);border:1px solid rgba(201,162,39,0.2);border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:1.5rem;color:var(--c-gold);margin-bottom:20px;">
                                <i class="bi bi-bullseye" aria-hidden="true"></i>
                            </div>
                            <h3 class="service-title">{{ __('front.home.about.mission_title') }}</h3>
                            <p class="service-desc">{{ __('front.home.about.mission_desc') }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="service-card" style="border-top:3px solid var(--c-blue-light);">
                        <div class="service-body">
                            <div style="width:56px;height:56px;background:rgba(0,168,232,0.12);border:1px solid rgba(0,168,232,0.2);border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:1.5rem;color:var(--c-blue-light);margin-bottom:20px;">
                                <i class="bi bi-eye-fill" aria-hidden="true"></i>
                            </div>
                            <h3 class="service-title">{{ __('front.home.about.vision_title') }}</h3>
                            <p class="service-desc">{{ __('front.home.about.vision_desc') }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="service-card" style="border-top:3px solid var(--c-gold);">
                        <div class="service-body">
                            <div style="width:56px;height:56px;background:rgba(201,162,39,0.12);border:1px solid rgba(201,162,39,0.2);border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:1.5rem;color:var(--c-gold);margin-bottom:20px;">
                                <i class="bi bi-stars" aria-hidden="true"></i>
                            </div>
                            <h3 class="service-title">{{ __('front.about.values_title') }}</h3>
                            <p class="service-desc">{{ __('front.about.values_desc') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <x-front.cta-section :title="__('front.about.cta_title')" :description="__('front.about.cta_desc')">
        <a href="{{ route('contact', app()->getLocale()) }}" class="btn-gold">
            <i class="bi bi-envelope-fill" aria-hidden="true"></i>
            <span>{{ __('buttons.contact_us') }}</span>
        </a>
        <a href="{{ route('services.index', app()->getLocale()) }}" class="btn-outline-gold">
            <i class="bi bi-grid-fill" aria-hidden="true"></i>
            <span>{{ __('navigation.services') }}</span>
        </a>
    </x-front.cta-section>

@endsection
