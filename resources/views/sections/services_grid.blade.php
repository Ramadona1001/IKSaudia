@php
    $locale = app()->getLocale();
    $featured = app(\App\Services\ServiceCatalogService::class)->publishedAll($locale);
@endphp
@if ($featured->isNotEmpty())
<section class="border-b border-white/10 py-20">
    <div class="mx-auto max-w-7xl px-4 lg:px-8">
        <div class="mb-12 flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
            <div>
                <h2 class="text-3xl font-bold text-white">{{ $translation?->title ?? __('navigation.services') }}</h2>
                @if ($translation?->subtitle)
                    <p class="mt-2 text-brand-steel">{{ $translation->subtitle }}</p>
                @endif
            </div>
            <a href="{{ route('services.index', $locale) }}" class="text-sm font-medium text-brand-accent hover:underline">
                {{ __('common.view_all') }}
            </a>
        </div>
        <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($featured as $service)
                @php $t = $service->translate($locale); @endphp
                @if ($t)
                    <a href="{{ route('services.show', [$locale, $t->slug]) }}"
                       class="rounded-xl border border-white/10 bg-brand-900/40 p-6 transition hover:border-brand-accent/40">
                        <h3 class="font-semibold text-white">{{ $t->title }}</h3>
                        @if ($t->summary)
                            <p class="mt-2 text-sm text-brand-steel line-clamp-2">{{ $t->summary }}</p>
                        @endif
                    </a>
                @endif
            @endforeach
        </div>
    </div>
</section>
@endif
