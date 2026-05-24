@props(['variant' => 'default'])

@php
    $variants = [
        'default' => 'bg-white/10 text-steel-200',
        'accent' => 'bg-accent/20 text-accent-light',
        'steel' => 'bg-steel-500/20 text-steel-200',
    ];
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center rounded-full px-3 py-1 text-caption font-medium '.($variants[$variant] ?? $variants['default'])]) }}>
    {{ $slot }}
</span>
