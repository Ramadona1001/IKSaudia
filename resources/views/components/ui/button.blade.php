@props([
    'variant' => 'primary',
    'size' => 'md',
    'href' => null,
    'type' => 'button',
])

@php
    $base = 'inline-flex items-center justify-center gap-2 font-semibold transition-all duration-300 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-accent disabled:opacity-50 disabled:pointer-events-none active:scale-[0.98]';
    $variants = [
        'primary' => 'bg-accent text-navy-950 hover:bg-accent-light shadow-glow hover:shadow-lg hover:-translate-y-0.5',
        'secondary' => 'border border-white/20 text-white hover:border-accent hover:text-accent bg-white/5 hover:bg-white/10 hover:-translate-y-0.5',
        'ghost' => 'text-steel-200 hover:text-white hover:bg-white/5',
        'outline' => 'border-2 border-accent/80 text-accent hover:bg-accent hover:text-navy-950 hover:border-accent',
    ];
    $sizes = [
        'sm' => 'text-xs px-4 py-2.5 rounded-lg',
        'md' => 'text-sm px-6 py-3 rounded-lg',
        'lg' => 'text-base px-8 py-4 rounded-xl',
    ];
    $classes = $base.' '.($variants[$variant] ?? $variants['primary']).' '.($sizes[$size] ?? $sizes['md']);
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif
