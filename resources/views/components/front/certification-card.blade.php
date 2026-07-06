@props([
    'name',
    'image',
    'url' => null,
])

@php
    $href = filled($url) && $url !== '#' ? $url : null;
    $tag = $href ? 'a' : 'div';
@endphp

<{{ $tag }}
    @if ($href) href="{{ $href }}" target="_blank" rel="noopener noreferrer" @endif
    {{ $attributes->class('certification-card') }}
    aria-label="{{ $name }}"
>
    <img
        src="{{ $image }}"
        alt="{{ $name }}"
        class="certification-card-logo"
        loading="lazy"
        decoding="async"
        width="160"
        height="72"
    >
</{{ $tag }}>
