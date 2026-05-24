@props([
    'overline' => null,
    'title',
    'subtitle' => null,
    'compact' => false,
])

<section {{ $attributes->merge(['class' => 'relative overflow-hidden border-b border-white/10 bg-industrial-gradient']) }}>
    <div class="hero-beam opacity-40" aria-hidden="true"></div>
    <div class="absolute inset-0 bg-industrial-grid opacity-40" aria-hidden="true"></div>
    <div class="absolute inset-0 bg-gradient-to-b from-transparent to-navy-950/90" aria-hidden="true"></div>

    <div class="container-iks relative z-10 reveal {{ $compact ? 'py-20 lg:py-24' : 'py-24 lg:py-32' }}">
        @if ($overline)
            <p class="text-overline text-accent mb-4">{{ $overline }}</p>
        @endif

        @isset($breadcrumb)
            <div class="mb-6">{{ $breadcrumb }}</div>
        @endisset

        <h1 class="text-display-xl text-white max-w-4xl">{{ $title }}</h1>

        @if ($subtitle)
            <p class="mt-6 max-w-2xl text-lead">{{ $subtitle }}</p>
        @endif

        @if (isset($actions))
            <div class="mt-10 flex flex-wrap gap-4">{{ $actions }}</div>
        @endif
    </div>
</section>
