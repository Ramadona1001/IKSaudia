@props(['translation' => null, 'section' => null])

@php
    $locale = app()->getLocale();
    $aboutImageUrl = $section?->featured_image_url;
    $aboutBody = $translation?->bodyText();
    $aboutCtaUrl = $translation?->cta_url ?: route('page.show', [$locale, 'about-us']);
    $aboutCtaLabel = $translation?->cta_label ?: __('buttons.our_full_story');
@endphp

<section id="about" class="section-padding section-divider">
    <div class="container-iks">
        <div class="grid items-center gap-16 lg:grid-cols-2">
            <div class="reveal order-2 lg:order-1">
                <x-ui.section-heading
                    :overline="__('home.about.overline')"
                    :title="$translation?->title ?? __('home.about.default_title')"
                    :subtitle="$translation?->subtitle ?? __('home.about.default_subtitle')"
                />
                <div class="mt-8 prose-iks max-w-none">
                    <p>{{ $aboutBody ?: __('home.about.default_body') }}</p>
                </div>
                <div class="mt-10 flex flex-wrap gap-4">
                    <x-ui.button :href="$aboutCtaUrl" class="hover-shine">
                        {{ $aboutCtaLabel }}
                    </x-ui.button>
                    <x-ui.button :href="route('projects.index', $locale)" variant="outline">
                        {{ __('buttons.view_projects') }}
                    </x-ui.button>
                </div>
            </div>

            <div class="reveal-scale order-1 lg:order-2">
                <div class="relative">
                    <div class="absolute -inset-4 rounded-3xl bg-accent/10 blur-2xl" aria-hidden="true"></div>
                    <div class="glass-panel-elevated relative aspect-[4/5] overflow-hidden rounded-3xl sm:aspect-square lg:aspect-[4/5]">
                        @if ($aboutImageUrl)
                            <img src="{{ $aboutImageUrl }}" alt="{{ $translation?->title ?: __('home.about.default_title') }}" class="absolute inset-0 h-full w-full object-cover">
                        @endif
                        <div class="absolute inset-0 bg-industrial-gradient {{ $aboutImageUrl ? 'opacity-70' : '' }}"></div>
                        <div class="absolute inset-0 bg-industrial-grid opacity-40"></div>
                        <div class="absolute inset-0 flex flex-col justify-end p-8 lg:p-10">
                            <p class="text-overline text-accent">{{ __('common.location') }}</p>
                            <p class="mt-2 text-2xl font-bold text-white">{{ __('home.about.location_city') }}</p>
                            <p class="mt-2 text-sm text-steel-400">{{ __('home.about.location_detail') }}</p>
                        </div>
                        <div class="absolute top-8 end-8 rounded-xl border border-white/10 bg-navy-950/80 px-4 py-3 backdrop-blur">
                            <p class="text-caption text-steel-400">{{ __('home.about.sector_label') }}</p>
                            <p class="font-semibold text-accent">{{ __('home.about.sector_value') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
