@php $locale = app()->getLocale(); @endphp

<section class="section-padding-sm">
    <div class="container-iks reveal-scale">
        <div class="relative overflow-hidden rounded-3xl border border-accent/20">
            <div class="absolute inset-0 bg-gradient-to-br from-navy-800 via-navy-900 to-navy-950" aria-hidden="true"></div>
            <div class="absolute inset-0 bg-industrial-grid opacity-20" aria-hidden="true"></div>
            <div class="absolute -end-20 -top-20 h-64 w-64 rounded-full bg-accent/15 blur-3xl" aria-hidden="true"></div>

            <div class="relative flex flex-col items-center gap-10 px-8 py-16 text-center lg:flex-row lg:justify-between lg:px-16 lg:py-20 lg:text-start">
                <div class="max-w-2xl">
                    <p class="text-overline text-accent">{{ __('home.cta.partner_overline') }}</p>
                    <h2 class="mt-4 text-display-lg text-white">
                        {{ __('home.cta.default_title_long') }}
                    </h2>
                    <p class="mt-4 text-lead">
                        {{ __('home.cta.partner_subtitle_commercial') }}
                    </p>
                    <div class="mt-6 flex flex-wrap items-center justify-center gap-4 lg:justify-start text-caption text-steel-500">
                        <span class="flex items-center gap-2">
                            <svg class="h-4 w-4 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            {{ __('home.cta.response_24h_time') }}
                        </span>
                        <span class="flex items-center gap-2">
                            <svg class="h-4 w-4 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            {{ __('home.cta.certified_team') }}
                        </span>
                    </div>
                </div>
                <div class="flex flex-col gap-3 sm:flex-row shrink-0">
                    <x-ui.button :href="route('contact', $locale)" size="lg" class="hover-shine min-w-[200px] justify-center">
                        {{ __('buttons.book_a_consultation') }}
                    </x-ui.button>
                    <x-ui.button href="tel:+966138095254" variant="secondary" size="lg" class="min-w-[200px] justify-center">
                        {{ __('buttons.call_now') }}
                    </x-ui.button>
                </div>
            </div>
        </div>
    </div>
</section>
