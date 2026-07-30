@props([
    'icon' => null,
    'dotSize' => 'lg',
])

@php
    use App\Support\BootstrapIcon;

    $legacyGearIcons = ['bi-gear-fill', 'bi-gear-wide-connected', 'bi-gear'];
    $normalizedIcon = BootstrapIcon::normalize($icon);
    $useBrandDot = blank($normalizedIcon) || in_array($normalizedIcon, $legacyGearIcons, true);
@endphp

@if ($useBrandDot)
    <x-front.brand-dot :size="$dotSize" {{ $attributes }} />
@else
    <i {{ $attributes->class(BootstrapIcon::classes($icon)) }} aria-hidden="true"></i>
@endif
