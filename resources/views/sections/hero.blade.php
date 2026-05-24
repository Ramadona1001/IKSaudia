<section class="relative overflow-hidden border-b border-white/10">
    <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-brand-800/40 via-brand-950 to-brand-950"></div>
    <div class="relative mx-auto max-w-7xl px-4 py-24 lg:px-8 lg:py-32">
        <p class="mb-4 text-sm font-semibold uppercase tracking-[0.2em] text-brand-accent">
            {{ __('home.hero.section_overline') }}
        </p>
        <h1 class="max-w-3xl text-4xl font-bold leading-tight text-white md:text-5xl lg:text-6xl">
            {{ $translation?->title }}
        </h1>
        @if ($translation?->subtitle)
            <p class="mt-6 max-w-2xl text-lg text-brand-steel">{{ $translation->subtitle }}</p>
        @endif
        @if ($translation?->cta_label)
            <a href="{{ $translation->cta_url ?? '#' }}"
               class="mt-10 inline-flex items-center rounded-md bg-brand-accent px-6 py-3 text-sm font-semibold text-brand-950 transition hover:bg-amber-400">
                {{ $translation->cta_label }}
            </a>
        @endif
    </div>
</section>
