<section id="about" class="border-b border-white/10 py-20">
    <div class="mx-auto max-w-7xl px-4 lg:px-8">
        <div class="grid items-center gap-12 lg:grid-cols-2">
            @if ($section?->featured_image_url)
                <div class="overflow-hidden rounded-2xl border border-white/10">
                    <img src="{{ $section->featured_image_url }}" alt="{{ $translation?->title }}" class="h-full w-full object-cover">
                </div>
            @endif
            <div>
                <h2 class="text-3xl font-bold text-white">{{ $translation?->title }}</h2>
                @if ($translation?->subtitle)
                    <p class="mt-4 text-brand-steel">{{ $translation->subtitle }}</p>
                @endif
                @if ($translation?->bodyText())
                    <p class="mt-6 text-sm leading-relaxed text-brand-steel">{{ $translation->bodyText() }}</p>
                @endif
                @if ($translation?->cta_label && $translation?->cta_url)
                    <a href="{{ $translation->cta_url }}" class="mt-6 inline-block text-accent hover:underline">{{ $translation->cta_label }}</a>
                @endif
            </div>
            @if (! $section?->featured_image_url)
                <div class="rounded-2xl border border-white/10 bg-brand-900/50 p-8 text-sm leading-relaxed text-brand-steel">
                    {{ $translation?->bodyText() ?: __('home.about.snippet_fallback') }}
                </div>
            @endif
        </div>
    </div>
</section>

