@props([
    'name',
    'image' => null,
    'url' => null,
    'imageOnly' => false,
])

@php
    $href = filled($url) && $url !== '#' ? $url : null;
    $tag = $href ? 'a' : 'div';
    $showImage = filled($image);
    $showName = ! $imageOnly && ! $showImage;
@endphp

@if ($showImage || $showName)
    <{{ $tag }}
        @if ($href) href="{{ $href }}" target="_blank" rel="noopener noreferrer" @endif
        {{ $attributes->class('marquee-client') }}
        @if ($href || ($imageOnly && $showImage)) aria-label="{{ $name }}" @endif
    >
        @if ($showImage)
            <img
                src="{{ $image }}"
                alt="{{ $name }}"
                class="marquee-client-logo"
                loading="lazy"
                decoding="async"
            >
        @elseif ($showName)
            <span class="marquee-client-name">{{ $name }}</span>
        @endif
    </{{ $tag }}>
@endif
