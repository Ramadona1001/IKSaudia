@props([
    'size' => 'md',
])

@php
    $sizes = [
        'sm' => 10,
        'md' => 14,
        'lg' => 18,
        'xl' => 22,
    ];

    $px = $sizes[$size] ?? (is_numeric($size) ? (int) $size : 14);
@endphp

<span
    {{ $attributes->class(['iks-brand-dot']) }}
    aria-hidden="true"
    style="--brand-dot-size: {{ $px }}px"
>
    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <circle cx="12" cy="12" r="9" fill="currentColor" opacity="0.18" />
        <circle cx="12" cy="12" r="5" fill="currentColor" />
    </svg>
</span>
