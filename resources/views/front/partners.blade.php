@extends('front.layouts.app')

@section('title', __('navigation.partners'))
@section('meta_description', __('front.partners.subtitle'))

@section('content')

    <x-front.page-hero
        :tag="__('front.partners.tag')"
        icon="bi-globe2"
        :title="__('front.partners.title')"
        :highlight="__('front.partners.highlight')"
        :subtitle="__('front.partners.subtitle')"
    />

    <x-front.breadcrumb :items="[['label' => __('front.partners.breadcrumb')]]" />

    <section class="section-pad bg-dark1">
        <div class="container">
            <x-front.section-heading
                :eyebrow="__('front.partners.tech_eyebrow')"
                :title="__('front.partners.tech_title')"
                :highlight="__('front.partners.tech_highlight')"
                :description="__('front.partners.tech_desc')"
                data-aos="fade-up"
            />

            @php
                $defaultPartners = [
                    ['name' => 'SIEMENS',       'icon' => 'bi-cpu-fill',             'type' => 'Technology Partner'],
                    ['name' => 'ABB GROUP',     'icon' => 'bi-lightning-charge-fill', 'type' => 'Automation Partner'],
                    ['name' => 'HONEYWELL',     'icon' => 'bi-thermometer-half',     'type' => 'Process Partner'],
                    ['name' => 'CATERPILLAR',   'icon' => 'bi-truck',                'type' => 'Equipment Partner'],
                    ['name' => 'EMERSON',       'icon' => 'bi-tools',                'type' => 'Instrumentation'],
                    ['name' => 'SULZER',        'icon' => 'bi-fuel-pump-fill',       'type' => 'Pumps & Equipment'],
                ];
            @endphp

            <div class="partners-grid partners-grid--page" data-aos="fade-up">
                @if ($partners->isNotEmpty())
                    @foreach ($partners as $partner)
                        @php $pt = $partner->translate(app()->getLocale()); @endphp
                        <x-front.partner-card
                            :name="$pt?->name ?? '—'"
                            :type="$pt?->description"
                            :url="$partner->website_url ?? '#'"
                            :image="$partner->featured_image_url"
                            icon="bi-cpu-fill"
                        />
                    @endforeach
                @else
                    @foreach ($defaultPartners as $p)
                        <x-front.partner-card :name="$p['name']" :type="$p['type']" :icon="$p['icon']" url="#" />
                    @endforeach
                @endif
            </div>
        </div>
    </section>

    @if (isset($certifications) && $certifications->isNotEmpty())
        <section class="section-pad bg-dark2">
            <div class="container">
                <x-front.section-heading
                    :eyebrow="__('front.partners.cert_eyebrow')"
                    :highlight="__('front.partners.cert_title')"
                    data-aos="fade-up"
                />

                <div class="partners-grid partners-grid--page" data-aos="fade-up">
                    @foreach ($certifications as $cert)
                        @php $ct = $cert->translate(app()->getLocale()); @endphp
                        <x-front.partner-card
                            :name="$ct?->title ?? $cert->issuer ?? '—'"
                            :type="$cert->issuer ?: __('common.certification')"
                            :image="$cert->featured_image_url"
                            icon="bi-patch-check-fill"
                            url="#"
                        />
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <x-front.cta-section :title="__('front.partners.cta_title')" :description="__('front.partners.cta_desc')" background="bg-dark1">
        <a href="{{ route('contact', app()->getLocale()) }}" class="btn-gold">
            <i class="bi bi-handshake-fill" aria-hidden="true"></i>
            <span>{{ __('front.partners.discuss_partnership') }}</span>
        </a>
    </x-front.cta-section>

@endsection
