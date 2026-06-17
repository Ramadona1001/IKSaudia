@props([
    'icon' => null,
    'dotSize' => 'lg',
])

@php
    $legacyGearIcons = ['bi-gear-fill', 'bi-gear-wide-connected', 'bi-gear'];
    $useBrandDot = blank($icon) || in_array($icon, $legacyGearIcons, true);
@endphp

@if ($useBrandDot)
    <x-front.brand-dot :size="$dotSize" {{ $attributes }} />
@else
    <i {{ $attributes->class("bi {$icon}") }} aria-hidden="true"></i>
@endif
