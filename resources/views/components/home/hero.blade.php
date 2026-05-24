@props([
    'slides' => collect(),
    'settings' => [],
    'translation' => null,
])

@php
    $locale = app()->getLocale();
    $activeSlides = $slides->filter(fn ($slide) => $slide->is_active && $slide->image);
    $hasSlides = $activeSlides->isNotEmpty();
    $autoplay = filter_var($settings['autoplay'] ?? true, FILTER_VALIDATE_BOOLEAN);
    $intervalMs = max(3000, (int) ($settings['interval_ms'] ?? 6000));

    $resolveUrl = function (?string $url) use ($locale): string {
        if (blank($url)) {
            return '#';
        }

        if (str_starts_with($url, 'http://') || str_starts_with($url, 'https://') || str_starts_with($url, 'tel:') || str_starts_with($url, 'mailto:')) {
            return $url;
        }

        if (str_starts_with($url, '/')) {
            return url($url);
        }

        return url("/{$locale}/".ltrim($url, '/'));
    };
@endphp

@if ($hasSlides)
    <section
        class="hero-slider"
        aria-roledescription="carousel"
        aria-label="{{ __('home.hero.slider_label') }}"
        x-data="heroSlider"
        data-total="{{ $activeSlides->count() }}"
        data-autoplay="{{ $autoplay && $activeSlides->count() > 1 ? '1' : '0' }}"
        data-interval="{{ $intervalMs }}"
        @mouseenter="pause()"
        @mouseleave="resume()"
    >
        <div class="hero-slider__track">
            @foreach ($activeSlides as $index => $slide)
                @php $t = $slide->translate($locale); @endphp
                <div
                    class="hero-slider__slide"
                    x-show="index === {{ $index }}"
                    x-transition:enter="transition ease-out duration-700"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="transition ease-in duration-500"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    @if ($index > 0) style="display: none;" @endif
                    role="group"
                    aria-roledescription="slide"
                    :aria-hidden="index !== {{ $index }}"
                >
                    <img
                        src="{{ $slide->imageUrl() }}"
                        alt="{{ $t?->title ?? '' }}"
                        class="hero-slider__image"
                        @if ($index === 0) fetchpriority="high" @else loading="lazy" @endif
                    >
                    <div class="hero-slider__overlay" aria-hidden="true"></div>
                    <div class="hero-slider__grid" aria-hidden="true"></div>

                    <div class="hero-slider__content container-iks-wide">
                        <div class="hero-slider__copy mx-auto max-w-3xl">
                            <x-ui.badge variant="accent" class="mb-8">
                                {{ __('home.hero.badge') }}
                            </x-ui.badge>

                            <h1 class="text-display-2xl text-white">
                                {{ $t?->title ?? __('home.hero.default_title') }}
                            </h1>

                            @if (filled($t?->description))
                                <p class="mt-8 mx-auto max-w-2xl text-lead">
                                    {{ $t->description }}
                                </p>
                            @endif

                            <div class="mt-12 flex flex-col items-center justify-center gap-4 sm:flex-row">
                                @if (filled($t?->button_text))
                                    <x-ui.button :href="$resolveUrl($t->button_url)" size="lg" class="hover-shine">
                                        {{ $t->button_text }}
                                    </x-ui.button>
                                @else
                                    <x-ui.button :href="route('contact', $locale)" size="lg" class="hover-shine">
                                        {{ __('buttons.start_project') }}
                                    </x-ui.button>
                                @endif
                                @if (filled($t?->secondary_button_text))
                                    <x-ui.button :href="$resolveUrl($t->secondary_button_url)" variant="secondary" size="lg">
                                        {{ $t->secondary_button_text }}
                                    </x-ui.button>
                                @else
                                    <x-ui.button :href="route('services.index', $locale)" variant="secondary" size="lg">
                                        {{ __('buttons.explore_capabilities') }}
                                    </x-ui.button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        @if ($activeSlides->count() > 1)
            <div class="hero-slider__controls">
                <button type="button" class="hero-slider__arrow" @click="prev()" aria-label="{{ __('common.previous') }}">
                    <svg class="h-5 w-5 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </button>
                <button type="button" class="hero-slider__arrow" @click="next()" aria-label="{{ __('common.next') }}">
                    <svg class="h-5 w-5 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>
            </div>

            <div class="hero-slider__dots" role="tablist" aria-label="{{ __('home.hero.slider_dots') }}">
                @foreach ($activeSlides as $i => $slide)
                    <button
                        type="button"
                        @click="goTo({{ $i }})"
                        class="hero-slider__dot"
                        :class="index === {{ $i }} ? 'is-active' : ''"
                        :aria-selected="index === {{ $i }}"
                        aria-label="{{ __('home.hero.slide_label', ['number' => $i + 1]) }}"
                    ></button>
                @endforeach
            </div>
        @endif

        <a href="#stats" class="absolute bottom-10 left-1/2 z-20 hidden -translate-x-1/2 flex-col items-center gap-2 text-caption text-steel-500 transition hover:text-accent animate-float md:flex">
            <span>{{ __('common.discover_more') }}</span>
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
        </a>
    </section>
@else
    <section class="hero-cinematic" aria-labelledby="hero-title">
        <x-home.industrial-background />
        <div class="absolute inset-x-0 bottom-0 z-[1] h-40 bg-gradient-to-t from-navy-950 to-transparent" aria-hidden="true"></div>

        <div class="container-iks-wide relative z-10 w-full py-28 lg:py-40">
            <div class="grid items-end gap-16 lg:grid-cols-12 lg:gap-14">
                <div class="lg:col-span-7">
                    <x-ui.badge variant="accent" class="mb-8">
                        {{ __('home.hero.badge') }}
                    </x-ui.badge>

                    <h1 id="hero-title" class="text-display-2xl text-white">
                        {{ $translation?->title ?? __('home.hero.default_title') }}
                    </h1>

                    <p class="mt-8 max-w-2xl text-lead">
                        {{ $translation?->subtitle ?? __('home.hero.default_subtitle') }}
                    </p>

                    <div class="mt-12 flex flex-col gap-4 sm:flex-row">
                        <x-ui.button :href="route('contact', $locale)" size="lg" class="hover-shine">
                            {{ __('buttons.start_project') }}
                        </x-ui.button>
                        <x-ui.button :href="route('services.index', $locale)" variant="secondary" size="lg">
                            {{ __('buttons.explore_capabilities') }}
                        </x-ui.button>
                    </div>
                </div>

                <div class="lg:col-span-5">
                    <div class="glass-panel-elevated rounded-3xl p-8 lg:p-10">
                        <p class="text-overline text-accent mb-6">{{ __('home.hero.why_title') }}</p>
                        <ul class="space-y-5" role="list">
                            @foreach (\App\Support\LocaleHelper::heroPointKeys() as $pointKey)
                                <li class="flex gap-4 text-sm text-steel-200">
                                    <span class="mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-full bg-accent/20 text-accent" aria-hidden="true">
                                        <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                    </span>
                                    {{ __('home.hero.points.'.$pointKey) }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <a href="#stats" class="absolute bottom-10 left-1/2 z-10 hidden -translate-x-1/2 flex-col items-center gap-2 text-caption text-steel-500 transition hover:text-accent animate-float md:flex">
            <span>{{ __('common.discover_more') }}</span>
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
        </a>
    </section>
@endif
