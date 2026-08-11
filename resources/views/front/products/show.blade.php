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
        ['section' => 'products', 'url' => route('products.index')],
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
                                    <i class="{{ \App\Support\BootstrapIcon::classes($product->icon, 'bi-box-seam') }}" aria-hidden="true"></i>
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

                        @if ($product->hasSpecificationPdf())
                            <div class="product-spec-card" data-aos="fade-up" data-aos-delay="50">
                                <div class="product-spec-card__icon" aria-hidden="true">
                                    <i class="bi bi-file-earmark-pdf-fill"></i>
                                </div>
                                <div class="product-spec-card__body">
                                    <h3 class="product-spec-card__title">{{ __('front.products.spec_pdf_title') }}</h3>
                                    <p class="product-spec-card__desc">{{ __('front.products.spec_pdf_desc') }}</p>
                                </div>
                                <button
                                    type="button"
                                    class="btn-gold product-spec-card__btn"
                                    data-spec-download-open
                                    data-product-slug="{{ $translation?->slug ?? $product->translate('en')?->slug }}"
                                    data-request-url="{{ route('products.spec-download-request', ['slug' => $translation?->slug ?? $product->translate('en')?->slug]) }}"
                                >
                                    <i class="bi bi-download" aria-hidden="true"></i>
                                    <span>{{ __('front.products.download_spec_pdf') }}</span>
                                </button>
                            </div>
                        @endif

                        <div class="product-detail-split__actions">
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

@if ($product->hasSpecificationPdf())
    @push('modals')
        <div class="modal fade product-spec-modal" id="productSpecDownloadModal" tabindex="-1" aria-labelledby="productSpecDownloadModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content product-spec-modal__content">
                    <div class="modal-header product-spec-modal__header">
                        <h2 class="modal-title product-spec-modal__title" id="productSpecDownloadModalLabel">
                            {{ __('front.products.spec_request_title') }}
                        </h2>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('common.close') }}"></button>
                    </div>
                    <div class="modal-body product-spec-modal__body">
                        <p class="product-spec-modal__intro">{{ __('front.products.spec_request_intro') }}</p>

                        <div class="product-spec-modal__alert product-spec-modal__alert--success" id="product-spec-success" hidden role="status">
                            <i class="bi bi-check-circle-fill" aria-hidden="true"></i>
                            <span id="product-spec-success-text"></span>
                        </div>

                        <div class="product-spec-modal__alert product-spec-modal__alert--error" id="product-spec-error" hidden role="alert">
                            <i class="bi bi-exclamation-triangle-fill" aria-hidden="true"></i>
                            <span id="product-spec-error-text"></span>
                        </div>

                        <form id="product-spec-download-form" class="product-spec-modal__form" novalidate>
                            @csrf
                            {{-- <input type="text" name="website" value="" tabindex="-1" autocomplete="off" class="visually-hidden" aria-hidden="true"> --}}

                            <div class="form-group">
                                <label class="form-label" for="spec-name">{{ __('front.products.spec_request_name') }} <span class="text-gold">*</span></label>
                                <input id="spec-name" name="name" type="text" class="form-control-custom" placeholder="{{ __('front.products.spec_request_name_placeholder') }}" required maxlength="120" autocomplete="name">
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="spec-email">{{ __('front.products.spec_request_email') }} <span class="text-gold">*</span></label>
                                <input id="spec-email" name="email" type="email" class="form-control-custom" placeholder="{{ __('front.products.spec_request_email_placeholder') }}" required maxlength="255" autocomplete="email">
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="spec-phone">{{ __('front.products.spec_request_phone') }} <span class="text-gold">*</span></label>
                                <input id="spec-phone" name="phone" type="tel" class="form-control-custom" placeholder="{{ __('front.products.spec_request_phone_placeholder') }}" required maxlength="30" autocomplete="tel">
                            </div>

                            <div class="form-group mb-0">
                                <label class="form-label" for="spec-company">{{ __('front.products.spec_request_company') }}</label>
                                <input id="spec-company" name="company" type="text" class="form-control-custom" placeholder="{{ __('front.products.spec_request_company_placeholder') }}" maxlength="255" autocomplete="organization">
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer product-spec-modal__footer">
                        <button type="button" class="btn-outline-gold product-spec-modal__btn-close" data-bs-dismiss="modal">{{ __('common.close') }}</button>
                        <button type="submit" form="product-spec-download-form" class="btn-gold product-spec-modal__btn-submit" id="product-spec-submit">
                            <i class="bi bi-send-fill" aria-hidden="true"></i>
                            <span>{{ __('front.products.spec_request_submit') }}</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endpush
@endif
