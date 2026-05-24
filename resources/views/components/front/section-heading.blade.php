@props([
    'eyebrow' => null,
    'title' => null,
    'highlight' => null,
    'description' => null,
    'align' => 'center',
])

<div {{ $attributes->merge(['class' => $align === 'center' ? 'text-center mb-5' : 'mb-5']) }}>
    @if ($eyebrow)
        <div class="section-eyebrow">{{ $eyebrow }}</div>
    @endif

    @if ($title || $highlight)
        <h2 class="section-title">
            @if ($title)<span>{{ $title }}</span>@endif
            @if ($highlight)<span class="accent">{{ $highlight }}</span>@endif
        </h2>
    @endif

    @if ($description)
        <p class="section-desc {{ $align === 'center' ? 'mx-auto' : '' }}">{{ $description }}</p>
    @endif

    {{ $slot }}
</div>
