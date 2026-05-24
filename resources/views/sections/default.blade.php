@if ($translation?->title)
<section class="border-b border-white/10 py-16">
    <div class="mx-auto max-w-7xl px-4 lg:px-8">
        <h2 class="text-2xl font-bold text-white">{{ $translation->title }}</h2>
        @if ($translation->subtitle)
            <p class="mt-3 text-brand-steel">{{ $translation->subtitle }}</p>
        @endif
    </div>
</section>
@endif
