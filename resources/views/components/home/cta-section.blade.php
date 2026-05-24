@props([
    'variant' => 'primary',
    'title' => null,
    'subtitle' => null,
])

@php $locale = app()->getLocale(); @endphp

@if ($variant === 'inline')
    <section class="section-padding-tight">
        <div class="container-iks reveal">
            <div class="flex flex-col items-center justify-between gap-8 rounded-2xl border border-white/10 bg-navy-900/60 px-8 py-10 sm:flex-row">
                <div>
                    <p class="text-overline text-accent">{{ __('home.cta.ready') }}</p>
                    <p class="mt-2 text-xl font-bold text-white">
                        {{ $title ?? __('home.cta.default_title') }}
                    </p>
                </div>
                <x-ui.button :href="route('contact', $locale)" class="hover-shine shrink-0">
                    {{ __('buttons.contact_us') }}
                </x-ui.button>
            </div>
        </div>
    </section>
@else
    <section class="section-padding-sm" id="contact-cta">
        <div class="container-iks reveal-scale">
            <div class="relative overflow-hidden rounded-3xl border border-accent/20">
                <div class="absolute inset-0 bg-gradient-to-br from-navy-800 via-navy-900 to-navy-950" aria-hidden="true"></div>
                <div class="absolute inset-0 bg-industrial-grid opacity-20" aria-hidden="true"></div>
                <div class="absolute -end-20 -top-20 h-72 w-72 rounded-full bg-accent/15 blur-3xl" aria-hidden="true"></div>

                <div class="relative flex flex-col items-center gap-10 px-8 py-16 text-center lg:flex-row lg:justify-between lg:px-16 lg:py-20 lg:text-start">
                    <div class="max-w-2xl">
                        <p class="text-overline text-accent">{{ __('home.cta.partner_overline') }}</p>
                        <h2 class="mt-4 text-display-lg text-white">
                            {{ $title ?? __('home.cta.default_title_long') }}
                        </h2>
                        <p class="mt-4 text-lead">
                            {{ $subtitle ?? __('home.cta.partner_subtitle') }}
                        </p>
                        <ul class="mt-8 flex flex-wrap justify-center gap-6 lg:justify-start text-caption text-steel-400">
                            <li class="flex items-center gap-2">
                                <svg class="h-4 w-4 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                {{ __('home.cta.response_24h') }}
                            </li>
                            <li class="flex items-center gap-2">
                                <svg class="h-4 w-4 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                {{ __('home.cta.certified_teams') }}
                            </li>
                        </ul>
                    </div>
                    <div class="flex w-full flex-col gap-3 sm:w-auto sm:flex-row shrink-0">
                        <x-ui.button :href="route('contact', $locale)" size="lg" class="hover-shine justify-center min-w-[200px]">
                            {{ __('buttons.book_consultation') }}
                        </x-ui.button>
                        <x-ui.button href="tel:+966138095254" variant="secondary" size="lg" class="justify-center min-w-[200px]">
                            {{ __('buttons.call_now') }}
                        </x-ui.button>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif
