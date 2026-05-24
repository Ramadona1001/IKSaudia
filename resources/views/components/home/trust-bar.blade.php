@php $locale = app()->getLocale(); @endphp

<section id="trust" class="section-padding-tight border-b border-white/10 bg-navy-900/80 section-divider">
    <div class="container-iks">
        <div class="reveal flex flex-col items-center gap-10 lg:flex-row lg:justify-between">
            <p class="text-overline text-steel-400 text-center lg:text-start">
                {{ __('home.trust.title') }}
            </p>
            <div class="flex flex-wrap items-center justify-center gap-4 sm:gap-6">
                @foreach (['ISO 9001', 'ASME', 'API', 'ASTM', 'SABER'] as $cert)
                    <span class="group flex items-center gap-2 rounded-xl border border-white/10 bg-navy-950/60 px-5 py-3 text-sm font-semibold text-steel-300 transition duration-300 hover:border-accent/40 hover:text-white hover:shadow-glow-sm">
                        <span class="h-1.5 w-1.5 rounded-full bg-accent/80 transition group-hover:scale-125"></span>
                        {{ $cert }}
                    </span>
                @endforeach
            </div>
        </div>
    </div>
</section>
