@props(['certifications'])

@php
    $locale = app()->getLocale();
    $fallbackCerts = ['ISO 9001', 'ASME', 'API', 'ASTM', 'SABER'];
@endphp

<section id="trust" class="section-padding section-divider relative overflow-hidden bg-navy-900/50">
    <div class="absolute inset-0 bg-industrial-grid-fine opacity-20" aria-hidden="true"></div>

    <div class="container-iks relative">
        <div class="reveal mx-auto max-w-3xl text-center">
            <x-ui.section-heading
                align="center"
                :overline="__('home.certifications.overline')"
                :title="__('home.certifications.title')"
                :subtitle="__('home.certifications.subtitle')"
            />
        </div>

        @if ($certifications->isNotEmpty())
            <div class="mt-14 grid gap-4 sm:grid-cols-2 lg:grid-cols-4 reveal">
                @foreach ($certifications as $i => $cert)
                    @php $ct = $cert->translate($locale); @endphp
                    @if ($ct)
                        <article class="card-premium p-6 text-center hover-shine reveal-stagger-{{ min($i + 1, 6) }}">
                            <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-accent/10 ring-1 ring-accent/25">
                                <span class="text-lg font-bold text-accent">{{ $cert->issuer ?: mb_substr($ct->title, 0, 4) }}</span>
                            </div>
                            <h3 class="font-bold text-white">{{ $ct->title }}</h3>
                            @if ($ct->description)
                                <p class="mt-2 text-sm text-steel-400">{{ $ct->description }}</p>
                            @endif
                        </article>
                    @endif
                @endforeach
            </div>
        @else
            <div class="mt-14 flex flex-wrap justify-center gap-4 reveal">
                @foreach ($fallbackCerts as $name)
                    <span class="group flex items-center gap-2 rounded-xl border border-white/10 bg-navy-950/60 px-6 py-4 text-sm font-semibold text-steel-300 transition duration-300 hover:border-accent/40 hover:text-white hover:shadow-glow-sm">
                        <span class="h-2 w-2 rounded-full bg-accent transition group-hover:scale-125" aria-hidden="true"></span>
                        {{ $name }}
                    </span>
                @endforeach
            </div>
        @endif

        <div class="mt-16 reveal flex flex-wrap items-center justify-center gap-8 border-t border-white/10 pt-12 text-center lg:justify-between lg:text-start">
            <div class="flex items-center gap-3">
                <svg class="h-8 w-8 text-accent shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
                <div>
                    <p class="font-semibold text-white">{{ __('home.certifications.safety_title') }}</p>
                    <p class="text-caption text-steel-500">{{ __('home.certifications.safety_caption') }}</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <svg class="h-8 w-8 text-accent shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/>
                </svg>
                <div>
                    <p class="font-semibold text-white">{{ __('home.certifications.local_title') }}</p>
                    <p class="text-caption text-steel-500">{{ __('home.certifications.local_caption') }}</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <svg class="h-8 w-8 text-accent shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
                <div>
                    <p class="font-semibold text-white">{{ __('home.certifications.field_title') }}</p>
                    <p class="text-caption text-steel-500">{{ __('home.certifications.field_caption') }}</p>
                </div>
            </div>
        </div>
    </div>
</section>
