@extends('front.layouts.app')

@section('title', __('navigation.services'))
@section('meta_description', __('front.services.subtitle'))

@section('content')

    <x-front.page-hero
        :tag="__('front.services.tag')"
        icon="bi-gear-fill"
        :title="__('front.services.title')"
        :highlight="__('front.services.highlight')"
        :subtitle="__('front.services.subtitle')"
    />

    <x-front.breadcrumb :items="[['label' => __('front.services.breadcrumb')]]" />

    {{-- Services Grid --}}
    <section class="section-pad services-index-section">
        <div class="container">
            <div class="row g-4">
                @forelse ($services as $service)
                    <div class="col-lg-4 col-md-6">
                        <x-front.service-card :service="$service" :index="$loop->iteration" :delay="$loop->index * 100" />
                    </div>
                @empty
                    <div class="col-12">
                        <p class="text-center services-index-empty">{{ __('front.services.no_services') }}</p>
                    </div>
                @endforelse
            </div>

            @if ($services instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator && $services->hasPages())
                <div class="mt-5">{{ $services->withQueryString()->links() }}</div>
            @endif
        </div>
    </section>

    {{-- Why Choose Us --}}
    <section class="section-pad services-index-why">
        <div class="container">
            <x-front.section-heading
                :eyebrow="__('front.services.why_eyebrow')"
                :highlight="__('front.services.why_title')"
                data-aos="fade-up"
            />

            <div class="row g-4">
                @php
                    $edges = [
                        ['key' => 'aramco',   'icon' => 'bi-patch-check-fill'],
                        ['key' => 'workforce','icon' => 'bi-people-fill'],
                        ['key' => 'safety',   'icon' => 'bi-shield-fill-check'],
                        ['key' => 'delivery', 'icon' => 'bi-graph-up-arrow'],
                    ];
                @endphp

                @foreach ($edges as $i => $edge)
                    <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="{{ $i * 100 }}">
                        <div class="about-stat-item services-edge-card">
                            <div class="services-edge-icon">
                                <i class="bi {{ $edge['icon'] }}" aria-hidden="true"></i>
                            </div>
                            <h4 class="services-edge-title">{{ __('front.services.edge.'.$edge['key'].'.title') }}</h4>
                            <p class="services-edge-desc">{{ __('front.services.edge.'.$edge['key'].'.desc') }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <x-front.cta-section :title="__('front.services.cta_title')" :description="__('front.services.cta_desc')" background="bg-dark1">
        <a href="{{ route('contact', app()->getLocale()) }}" class="btn-gold">
            <i class="bi bi-chat-dots-fill" aria-hidden="true"></i>
            <span>{{ __('front.services.discuss_project') }}</span>
        </a>
    </x-front.cta-section>

@endsection
