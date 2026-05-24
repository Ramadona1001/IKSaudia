@extends('front.layouts.app')

@section('title', __('navigation.industries'))
@section('meta_description', __('front.industries.subtitle'))

@section('content')

    <x-front.page-hero
        :tag="__('front.industries.tag')"
        icon="bi-grid-3x3-gap-fill"
        :title="__('front.industries.title')"
        :highlight="__('front.industries.highlight')"
        :subtitle="__('front.industries.subtitle')"
    />

    <x-front.breadcrumb :items="[['label' => __('front.industries.breadcrumb')]]" />

    <section class="section-pad bg-dark1">
        <div class="container">
            <div class="row g-4">
                @forelse ($industries as $industry)
                    <div class="col-lg-4 col-md-6">
                        <x-front.industry-card :industry="$industry" :index="$loop->iteration" :delay="($loop->index % 3) * 100" expanded class="industry-card-tall" style="height:450px;" />
                    </div>
                @empty
                    <div class="col-12">
                        <p class="text-center" style="color:var(--c-muted);padding:60px 0;">{{ __('front.industries.no_industries') }}</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <x-front.cta-section :title="__('front.industries.cta_title')" :description="__('front.industries.cta_desc')">
        <a href="{{ route('contact', app()->getLocale()) }}" class="btn-gold">
            <i class="bi bi-chat-dots-fill" aria-hidden="true"></i>
            <span>{{ __('front.industries.talk_experts') }}</span>
        </a>
    </x-front.cta-section>

@endsection
