@extends('front.layouts.app')

@php
    $locale = app()->getLocale();
    $title = $translation?->title ?? '—';
    $summary = $translation?->summary ?? '';
    $body = $translation?->body ?? '';
    $parentT = $product->parent?->translate($locale);
    $children = $product->children->filter(fn($c) => $c->translate($locale));
@endphp

@section('title', $title)
@section('meta_description', $seo?->meta_description ?: ($summary ?: __('front.products.subtitle')))

@push('seo')
    @if ($seo?->meta_keywords)
        <meta name="keywords" content="{{ $seo->meta_keywords }}">
    @endif
@endpush

@section('content')

    <x-front.page-hero :tag="__('navigation.products')" :icon="$product->icon ?: 'bi-box-seam-fill'" :title="$title" :subtitle="$summary" />

    <x-front.breadcrumb :items="array_filter([
        ['label' => __('navigation.products'), 'url' => route('products.index')],
        $parentT ? ['label' => $parentT->title, 'url' => route('products.show', $parentT->slug)] : null,
        ['label' => $title],
    ])" />

    <section class="section-pad bg-dark1">
        <div class="container">
            @if ($children->isNotEmpty())
                <div class="row g-4 mb-5">
                    @foreach ($children as $child)
                        <div class="col-lg-4 col-md-6">
                            <x-front.product-card :product="$child" :index="$loop->iteration" :delay="($loop->index % 3) * 100" expanded
                                class="industry-card-tall" style="height:420px;" />
                        </div>
                    @endforeach
                </div>
            @endif

            <div class="row g-5 align-items-start product-detail-layout">
                <div @class([
                    'product-detail-main',
                    'col-12' => ! $parentT,
                    'col-lg-8 col-md-7' => (bool) $parentT,
                ])>
                    <div data-aos="fade-up">
                        @if ($product->featured_image_url)
                            <div class="product-detail-hero-img"
                                style="background-image:url('{{ $product->featured_image_url }}');"></div>
                        @endif

                        @if ($body)
                            <h2 class="section-title mb-3">{{ __('front.products.about_product') }}</h2>
                            @if ($summary)
                                <p class="section-desc mb-4">{{ $summary }}</p>
                            @endif
                            <div class="prose-light product-detail-prose">{!! safe_html($body) !!}</div>
                        @elseif ($summary && $children->isEmpty())
                            <p class="section-desc">{{ $summary }}</p>
                        @endif

                        @if ($product->pdfUrl())
                            <a href="{{ $product->pdfUrl() }}" class="btn-gold mt-4" target="_blank" rel="noopener">
                                <i class="bi bi-file-earmark-pdf" aria-hidden="true"></i>
                                <span>{{ __('front.products.view_pdf') }}</span>
                            </a>
                        @endif
                    </div>
                </div>

                {{-- @if ($parentT)
                    <div class="col-lg-4 col-md-5 product-detail-aside">
                        <aside class="product-detail-sidebar" data-aos="fade-up" data-aos-delay="100">
                            <h4 class="product-detail-sidebar-label">{{ __('front.products.category') }}</h4>
                            <a href="{{ route('products.show', $parentT->slug) }}" class="product-detail-sidebar-link">
                                {{ $parentT->title }}
                            </a>
                        </aside>
                    </div>
                @endif --}}
            </div>
        </div>
    </section>

    <x-front.cta-section :title="__('front.products.cta_title')" :description="__('front.products.cta_desc')">
        <a href="{{ route('contact', $locale) }}" class="btn-gold">
            <i class="bi bi-chat-dots-fill" aria-hidden="true"></i>
            <span>{{ __('front.products.talk_experts') }}</span>
        </a>
    </x-front.cta-section>

@endsection