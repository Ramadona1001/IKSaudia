@props([
    'product',
    'index' => 1,
    'delay' => 0,
    'expanded' => false,
])

@php
    $locale = app()->getLocale();
    $pt = $product->translate($locale);
    if (! $pt) { return; }

    $icon = $product->icon ?: 'bi-box-seam';
    $iconClasses = ['ind-icon-blue', 'ind-icon-gold', 'ind-icon-green', 'ind-icon-red', 'ind-icon-purple', 'ind-icon-teal'];
    $iconClass = $iconClasses[($index - 1) % count($iconClasses)];
    $bgClass = 'ind-bg-'.((($index - 1) % 6) + 1);
    $img = $product->featured_image_url ?? null;
    $href = route('products.show', $pt->slug);
@endphp

<a href="{{ $href }}"
   {{ $attributes->merge(['class' => 'industry-card industry-card--link']) }}
   data-aos="fade-up"
   data-aos-delay="{{ $delay }}"
   aria-label="{{ $pt->title }}">
    @if ($img)
        <div class="industry-card-bg" style="background-image:url('{{ $img }}');background-size:cover;background-position:center;"></div>
    @else
        <div class="industry-card-bg {{ $bgClass }}"></div>
    @endif
    <div class="industry-card-overlay"></div>
    <div class="industry-card-content">
        <div class="industry-icon {{ $iconClass }}"><i class="bi {{ $icon }}" aria-hidden="true"></i></div>
        <h3 class="industry-card-title">{{ $pt->title }}</h3>
        @if ($pt->summary)
            <p class="industry-card-desc" @if ($expanded) style="opacity:1;max-height:none;" @endif>
                {{ \Illuminate\Support\Str::limit(strip_tags($pt->summary), 180) }}
            </p>
        @endif
        <span class="industry-card-link" @if ($expanded) style="opacity:1;transform:none;" @endif>
            <span>{{ __('common.explore') }}</span>
            <i class="bi bi-arrow-right" aria-hidden="true"></i>
        </span>
    </div>
</a>
