@props([
    'count' => 0,
    'suffix' => '',
    'label' => null,
    'variant' => 'gold',
    'delay' => 0,
])

<div class="about-stat-item" data-aos="zoom-in" data-aos-delay="{{ $delay }}">
    <div class="about-stat-num {{ $variant }}" data-count="{{ (int) $count }}" data-suffix="{{ $suffix }}">0{{ $suffix }}</div>
    <div class="about-stat-label">{{ $label }}</div>
</div>
