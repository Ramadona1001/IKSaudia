@props([
    'name',
    'image' => null,
    'url' => null,
])

@php
    $href = filled($url) && $url !== '#' ? $url : null;
    $tag = $href ? 'a' : 'div';
@endphp

<{{ $tag }}
    @if ($href) href="{{ $href }}" target="_blank" rel="noopener noreferrer" @endif
    {{ $attributes->class('marquee-client') }}
    @if ($href) aria-label="{{ $name }}" @endif
>
    @if ($image)
        <img
            src="{{ $image }}"
            alt="{{ $name }}"
            class="marquee-client-logo"
            loading="lazy"
            decoding="async"
        >
    @else
        <span class="marquee-client-name">{{ $name }}</span>
    @endif
</{{ $tag }}>
