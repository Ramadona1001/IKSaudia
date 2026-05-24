@props([
    'href' => null,
    'padding' => true,
])

@php
    $classes = 'card-premium group '.($padding ? 'p-6 lg:p-8' : '');
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        <div class="relative z-[1]">{{ $slot }}</div>
    </a>
@else
    <div {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </div>
@endif
