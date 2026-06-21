@props([
    'title',
    'description',
    'icon' => 'bi-bullseye',
    'variant' => 'mission',
])

<div {{ $attributes->merge(['class' => "foundation-card foundation-card--{$variant}"]) }}>
    <div class="foundation-card-icon" aria-hidden="true">
        <i class="bi {{ $icon }}"></i>
    </div>
    <h3 class="foundation-card-title">{{ $title }}</h3>
    <p class="foundation-card-text">{{ $description }}</p>
</div>
