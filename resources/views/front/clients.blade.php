@extends('front.layouts.app')

@section('title', __('navigation.clients'))
@section('meta_description', __('front.clients.subtitle'))

@section('content')

    <x-front.page-hero
        :tag="__('front.clients.tag')"
        icon="bi-people-fill"
        :title="__('front.clients.title')"
        :highlight="__('front.clients.highlight')"
        :subtitle="__('front.clients.subtitle')"
    />

    <x-front.breadcrumb :items="[['label' => __('front.clients.breadcrumb')]]" />

    {{-- Stats --}}
    <section style="padding:50px 0;background:var(--c-dark2);border-bottom:1px solid rgba(255,255,255,0.04);">
        <div class="container">
            <div class="row g-4 text-center">
                <div class="col-md-3 col-6">
                    <x-front.stat-counter :count="150" suffix="+" :label="__('front.clients.stats.enterprise')" variant="gold" :delay="0" />
                </div>
                <div class="col-md-3 col-6">
                    <x-front.stat-counter :count="12" :label="__('front.clients.stats.countries')" variant="blue" :delay="100" />
                </div>
                <div class="col-md-3 col-6">
                    <x-front.stat-counter :count="98" suffix="%" :label="__('front.clients.stats.satisfaction')" variant="gold" :delay="200" />
                </div>
                <div class="col-md-3 col-6">
                    <x-front.stat-counter :count="500" suffix="+" :label="__('front.clients.stats.projects')" variant="blue" :delay="300" />
                </div>
            </div>
        </div>
    </section>

    {{-- Client Grid --}}
    <section class="section-pad bg-dark1">
        <div class="container">
            <x-front.section-heading
                :eyebrow="__('front.clients.eyebrow')"
                :highlight="__('front.clients.title2')"
                data-aos="fade-up"
            />

            <div class="clients-logo-grid" data-aos="fade-up" data-aos-delay="100" role="list" aria-label="Client logos" style="grid-template-columns:repeat(auto-fit,minmax(140px,1fr));">
                @php
                    $defaultClients = [
                        ['name' => 'ARAMCO',         'icon' => 'bi-droplet-fill'],
                        ['name' => 'SABIC',          'icon' => 'bi-flask-fill'],
                        ['name' => 'SEC',            'icon' => 'bi-lightning-fill'],
                        ['name' => 'NEOM',           'icon' => 'bi-building-fill'],
                        ['name' => 'MAADEN',         'icon' => 'bi-gem'],
                        ['name' => 'STC',            'icon' => 'bi-wifi'],
                        ['name' => 'ACWA',           'icon' => 'bi-sun-fill'],
                        ['name' => 'SBG',            'icon' => 'bi-hammer'],
                        ['name' => 'SIPCHEM',        'icon' => 'bi-cpu-fill'],
                        ['name' => 'TASNEE',         'icon' => 'bi-gear-fill'],
                        ['name' => 'MODON',          'icon' => 'bi-house-door-fill'],
                        ['name' => 'RCJY',           'icon' => 'bi-hospital-fill'],
                        ['name' => 'SWCC',           'icon' => 'bi-water'],
                        ['name' => 'ARAMCO GAS',     'icon' => 'bi-fuel-pump-fill'],
                        ['name' => 'SAUDI RAILWAYS', 'icon' => 'bi-minecart-loaded'],
                    ];
                @endphp

                @if ($clients->isNotEmpty())
                    @foreach ($clients as $client)
                        @php $ct = $client->translate(app()->getLocale()); @endphp
                        <x-front.client-logo
                            :name="$ct?->name ?? '—'"
                            :url="$client->website_url ?? '#'"
                            :image="$client->featured_image_url"
                            icon="bi-building-fill"
                        />
                    @endforeach
                @else
                    @foreach ($defaultClients as $c)
                        <x-front.client-logo :name="$c['name']" :icon="$c['icon']" url="#" />
                    @endforeach
                @endif
            </div>
        </div>
    </section>

    <x-front.cta-section :title="__('front.clients.cta_title')" :description="__('front.clients.cta_desc')">
        <a href="{{ route('contact', app()->getLocale()) }}" class="btn-gold">
            <i class="bi bi-handshake-fill" aria-hidden="true"></i>
            <span>{{ __('front.clients.start_partnership') }}</span>
        </a>
    </x-front.cta-section>

@endsection
