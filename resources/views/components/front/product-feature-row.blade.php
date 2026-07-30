@props([
    'product',
    'index' => 1,
    'delay' => 0,
])

@php
    $locale = app()->getLocale();
    $pt = $product->translate($locale);
    if (! $pt) {
        return;
    }

    $href = route('products.show', $pt->slug);
    $img = $product->featured_image_url;
@endphp

<a href="{{ $href }}"
   class="product-feature-row"
   data-aos="fade-up"
   data-aos-delay="{{ $delay }}"
   aria-label="{{ $pt->title }}">
    <div class="product-feature-row__media">
        @if ($img)
            <img src="{{ $img }}" alt="{{ $pt->title }}" class="product-feature-row__image" loading="lazy" decoding="async">
        @else
            <div class="product-feature-row__placeholder">
                <i class="{{ \App\Support\BootstrapIcon::classes($product->icon, 'bi-box-seam') }}" aria-hidden="true"></i>
            </div>
        @endif
    </div>
    <div class="product-feature-row__body">
        <h3 class="product-feature-row__title">{{ $pt->title }}</h3>
        @if ($pt->summary)
            <p class="product-feature-row__desc">{{ strip_tags($pt->summary) }}</p>
        @endif
        <span class="product-feature-row__cta">
            <span>{{ __('common.explore') }}</span>
            <i class="bi bi-arrow-right" aria-hidden="true"></i>
        </span>
    </div>
</a>
