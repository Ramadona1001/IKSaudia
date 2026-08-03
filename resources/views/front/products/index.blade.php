@extends('front.layouts.app')

@section('title', __('navigation.products'))
@section('meta_description', __('front.products.subtitle'))

@section('content')

    <x-front.page-hero
        :tag="__('front.products.tag')"
        icon="bi-box-seam-fill"
        :title="__('front.products.title')"
        :highlight="__('front.products.highlight')"
        :subtitle="__('front.products.subtitle')"
    />

    <x-front.breadcrumb :items="[['section' => 'products']]" />

    <section class="section-pad projects-index-section">
        <div class="container">
            <div class="row g-4">
                @forelse ($products as $product)
                    <div class="col-lg-4 col-md-6">
                        <x-front.product-card :product="$product" :index="$loop->iteration" :delay="($loop->index % 3) * 100" expanded class="industry-card-tall" style="height:450px;" />
                    </div>
                @empty
                    <div class="col-12">
                        <p class="text-center" style="color:var(--c-muted);padding:60px 0;">{{ __('front.products.no_products') }}</p>
                    </div>
                @endforelse
            </div>

            @if ($products->hasPages())
                <div class="mt-5 d-flex justify-content-center">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </section>

    <x-front.cta-section :title="__('front.products.cta_title')" :description="__('front.products.cta_desc')">
        <a href="{{ route('contact', app()->getLocale()) }}" class="btn-gold">
            <i class="bi bi-chat-dots-fill" aria-hidden="true"></i>
            <span>{{ __('front.products.talk_experts') }}</span>
        </a>
    </x-front.cta-section>

@endsection
