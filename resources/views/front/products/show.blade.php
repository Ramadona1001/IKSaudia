@extends('front.layouts.app')

@php
    $locale = app()->getLocale();
    $title = $translation?->title ?? '—';
    $summary = $translation?->summary ?? '';
    $body = $translation?->body ?? '';
    $parentT = $product->parent?->translate($locale);
    $children = $product->children->filter(fn ($c) => $c->translate($locale));
    $isCategoryPage = $children->isNotEmpty();
@endphp

@section('title', $title)
@section('meta_description', $seo?->meta_description ?: ($summary ?: __('front.products.subtitle')))

@push('seo')
    @if ($seo?->meta_keywords)
        <meta name="keywords" content="{{ $seo->meta_keywords }}">
    @endif
@endpush

@section('content')

    <x-front.page-hero
        :tag="$parentT?->title ?: __('navigation.products')"
        :icon="$product->icon ?: 'bi-box-seam-fill'"
        :title="$title"
        :subtitle="$isCategoryPage ? $summary : null"
    />

    <x-front.breadcrumb :items="array_filter([
        ['label' => __('navigation.products'), 'url' => route('products.index')],
        $parentT ? ['label' => $parentT->title, 'url' => route('products.show', $parentT->slug)] : null,
        ['label' => $title],
    ])" />

    <section class="section-pad products-show-section">
        <div class="container">
            @if ($isCategoryPage)
                <div class="product-feature-list">
                    @foreach ($children as $child)
                        <x-front.product-feature-row
                            :product="$child"
                            :index="$loop->iteration"
                            :delay="($loop->index % 3) * 80"
                        />
                    @endforeach
                </div>
            @else
                <div class="product-detail-split" data-aos="fade-up">
                    <div class="product-detail-split__media">
                        <div class="product-detail-image-card">
                            @if ($product->featured_image_url)
                                <img
                                    src="{{ $product->featured_image_url }}"
                                    alt="{{ $title }}"
                                    class="product-detail-image-card__img"
                                    loading="lazy"
                                    decoding="async"
                                >
                            @else
                                <div class="product-detail-image-card__placeholder">
                                    <i class="bi {{ $product->icon ?: 'bi-box-seam' }}" aria-hidden="true"></i>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="product-detail-split__content">
                        @if ($parentT)
                            <a href="{{ route('products.show', $parentT->slug) }}" class="product-detail-split__eyebrow">
                                {{ $parentT->title }}
                            </a>
                        @endif

                        <h2 class="product-detail-split__title">{{ $title }}</h2>

                        @if ($summary)
                            <p class="product-detail-split__summary">{{ $summary }}</p>
                        @endif

                        @if ($body && trim(strip_tags($body)) !== trim(strip_tags($summary)))
                            <div class="product-detail-prose">{!! safe_html($body) !!}</div>
                        @endif

                        <div class="product-detail-split__actions">
                            @if ($product->pdfUrl())
                                <a href="{{ $product->pdfUrl() }}" class="btn-gold" target="_blank" rel="noopener">
                                    <i class="bi bi-file-earmark-pdf" aria-hidden="true"></i>
                                    <span>{{ __('front.products.view_pdf') }}</span>
                                </a>
                            @endif

                            <a href="{{ route('contact', $locale) }}" class="btn-outline-gold">
                                <i class="bi bi-chat-dots-fill" aria-hidden="true"></i>
                                <span>{{ __('front.products.talk_experts') }}</span>
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>

    <x-front.cta-section :title="__('front.products.cta_title')" :description="__('front.products.cta_desc')">
        <a href="{{ route('contact', $locale) }}" class="btn-gold">
            <i class="bi bi-chat-dots-fill" aria-hidden="true"></i>
            <span>{{ __('front.products.talk_experts') }}</span>
        </a>
    </x-front.cta-section>

@endsection
