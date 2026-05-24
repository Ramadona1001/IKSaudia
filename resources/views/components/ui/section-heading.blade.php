@props([
    'overline' => null,
    'title',
    'subtitle' => null,
    'align' => 'start',
])

@php
    $alignClass = match ($align) {
        'center' => 'text-center mx-auto',
        'end' => 'text-end ms-auto',
        default => 'text-start',
    };
@endphp

<div {{ $attributes->merge(['class' => 'max-w-3xl '.$alignClass]) }}>
    @if ($overline)
        <p class="text-overline text-accent mb-3">{{ $overline }}</p>
    @endif
    <h2 class="text-display-lg text-white">{{ $title }}</h2>
    @if ($subtitle)
        <p class="mt-4 text-body-lg text-steel-300">{{ $subtitle }}</p>
    @endif
    @if (isset($actions))
        <div class="mt-8 flex flex-wrap gap-4 {{ $align === 'center' ? 'justify-center' : '' }}">
            {{ $actions }}
        </div>
    @endif
</div>
