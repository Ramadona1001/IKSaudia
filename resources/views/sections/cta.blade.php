<section class="border-b border-white/10 bg-brand-900/80 py-16">
    <div class="mx-auto max-w-4xl px-4 text-center lg:px-8">
        <h2 class="text-2xl font-bold text-white md:text-3xl">{{ $translation?->title }}</h2>
        @if ($translation?->subtitle)
            <p class="mt-4 text-brand-steel">{{ $translation->subtitle }}</p>
        @endif
        @if ($translation?->cta_label)
            <a href="{{ $translation->cta_url ?? '#' }}"
               class="mt-8 inline-flex rounded-md border border-brand-accent px-6 py-3 text-sm font-semibold text-brand-accent transition hover:bg-brand-accent hover:text-brand-950">
                {{ $translation->cta_label }}
            </a>
        @endif
    </div>
</section>
