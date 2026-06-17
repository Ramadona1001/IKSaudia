@props([
    'tag' => null,
    'icon' => 'bi-grid-3x3-gap-fill',
    'title' => null,
    'subtitle' => null,
    'highlight' => null,
])

<section {{ $attributes->merge(['class' => 'page-hero']) }}>
    <div class="page-hero-glow" aria-hidden="true"></div>
    <div class="container">
        <div class="page-hero-content">
            {{-- @if ($tag)
                <div class="page-hero-tag">
                    <i class="bi {{ $icon }}" aria-hidden="true"></i>
                    <span>{{ $tag }}</span>
                </div>
            @endif --}}

            @if ($title || $highlight)
                <h1 class="page-hero-title">
                    @if ($title){!! $title !!} @endif
                    @if ($highlight)<span class="text-gold">{{ $highlight }}</span>@endif
                </h1>
            @endif

            @if ($subtitle)
                <p class="page-hero-subtitle">{!! $subtitle !!}</p>
            @endif

            {{ $slot }}
        </div>
    </div>
</section>
