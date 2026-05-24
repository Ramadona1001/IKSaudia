@props([
    'service',
    'index' => 1,
    'delay' => 0,
])

@php
    $locale = app()->getLocale();
    $st = $service->translate($locale);
    if (! $st) { return; }

    $icon = $service->icon ?: 'bi-gear-fill';
    $bgClass = 'svc-bg-'.((($index - 1) % 6) + 1);
    $img = $service->featured_image_url ?? null;
@endphp

<article {{ $attributes->merge(['class' => 'service-card']) }} data-aos="fade-up" data-aos-delay="{{ $delay }}">
    <div class="service-img-wrap">
        @if ($img)
            <div class="service-img-bg" style="background-image:url('{{ $img }}');background-size:cover;background-position:center;"></div>
        @else
            <div class="service-img-bg {{ $bgClass }}"></div>
        @endif
        <div class="service-img-overlay"></div>
        <div class="service-icon-float"><i class="bi {{ $icon }}" aria-hidden="true"></i></div>
    </div>
    <div class="service-body">
        <div class="service-num">{{ str_pad((string) $index, 2, '0', STR_PAD_LEFT) }}</div>
        <h3 class="service-title">{{ $st->title }}</h3>
        @if ($st->summary)
            <p class="service-desc">{{ \Illuminate\Support\Str::limit($st->summary, 180) }}</p>
        @endif
        <a href="{{ route('services.show', [$locale, $st->slug]) }}"
           class="service-link"
           aria-label="{{ __('buttons.service_details') }} — {{ $st->title }}">
            <span>{{ __('common.read_more') }}</span>
            <i class="bi bi-arrow-right" aria-hidden="true"></i>
        </a>
    </div>
</article>
