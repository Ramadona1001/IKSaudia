@props([
    'industry',
    'index' => 1,
    'delay' => 0,
    'expanded' => false,
])

@php
    $locale = app()->getLocale();
    $it = $industry->translate($locale);
    if (! $it) { return; }

    $icon = $industry->icon ?: 'bi-grid-3x3-gap-fill';
    $iconClasses = ['ind-icon-blue', 'ind-icon-gold', 'ind-icon-green', 'ind-icon-red', 'ind-icon-purple', 'ind-icon-teal'];
    $iconClass = $iconClasses[($index - 1) % count($iconClasses)];
    $bgClass = 'ind-bg-'.((($index - 1) % 6) + 1);
    $img = $industry->featured_image_url ?? null;
@endphp

<article {{ $attributes->merge(['class' => 'industry-card']) }} data-aos="fade-up" data-aos-delay="{{ $delay }}">
    @if ($img)
        <div class="industry-card-bg" style="background-image:url('{{ $img }}');background-size:cover;background-position:center;"></div>
    @else
        <div class="industry-card-bg {{ $bgClass }}"></div>
    @endif
    <div class="industry-card-overlay"></div>
    <div class="industry-card-content">
        <div class="industry-icon {{ $iconClass }}">
            @if ($favicon)
                <img src="{{ $favicon }}" alt="" class="service-icon-favicon" loading="lazy" decoding="async" aria-hidden="true">
            @else
                <x-front.brand-dot size="lg" />
            @endif
        </div>
        <h3 class="industry-card-title">{{ $it->title }}</h3>
        @if ($it->summary)
            <p class="industry-card-desc" @if ($expanded) style="opacity:1;max-height:none;" @endif>
                {{ \Illuminate\Support\Str::limit($it->summary, 180) }}
            </p>
        @endif
        <a href="{{ route('industries.show', [$locale, $it->slug]) }}"
           class="industry-card-link"
           @if ($expanded) style="opacity:1;transform:none;" @endif>
            <span>{{ __('common.explore') }}</span>
            <i class="bi bi-arrow-right" aria-hidden="true"></i>
        </a>
    </div>
</article>
