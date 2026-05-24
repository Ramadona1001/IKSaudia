@php
    $locale = app()->getLocale();
    $otherLocale = $locale === 'ar' ? 'en' : 'ar';
    $switchParams = array_merge(request()->route()?->parameters() ?? [], ['locale' => $otherLocale]);
    $switchRoute = request()->route()?->getName() ?? 'home';

    $nav = $headerNav ?? [];
@endphp

<header
    x-data="siteHeader"
    @keydown.escape.window="closeAll()"
    class="fixed inset-x-0 top-0 z-50 transition-all duration-500 bg-navy-950/90"
    :class="scrolled ? 'bg-navy-950/90' : 'bg-navy-950/90'"
    role="banner"
>
    <div class="hidden border-b border-white/5 bg-navy-950/90 lg:block">
        <div class="container-iks flex h-9 items-center justify-between text-caption text-steel-400">
            <p class="truncate pe-4">{{ setting('general.site_name') ?: __('common.top_bar_location') }}</p>
            <div class="flex shrink-0 items-center gap-6">
                @foreach (($siteSettings ?? null)?->phones() ?? [] as $phone)
                    <a href="tel:{{ preg_replace('/\s+/', '', $phone['number'] ?? '') }}" class="transition hover:text-accent">{{ $phone['number'] }}</a>
                @endforeach
                @if ($email = ($siteSettings ?? null)?->primaryEmail())
                    <a href="mailto:{{ $email }}" class="transition hover:text-accent">{{ $email }}</a>
                @endif
            </div>
        </div>
    </div>

    <div class="container-iks">
        <div class="flex h-16 items-center justify-between gap-3 lg:h-[4.5rem]">
            <a href="{{ route('home', $locale) }}" class="group flex shrink-0 items-center gap-3 transition duration-300 group-hover:opacity-90" aria-label="{{ setting('general.site_name') ?: config('app.name') }}">
                <x-layout.site-logo />
            </a>

            <nav class="hidden items-center gap-0.5 lg:flex" aria-label="{{ __('navigation.main') }}">
                <a href="{{ route('home', $locale) }}" class="nav-link {{ request()->routeIs('home') ? 'nav-link-active' : '' }}">{{ __('navigation.home') }}</a>

                @foreach ($nav as $item)
                    @php
                        $itemKey = $item['key'] ?? 'nav-'.$loop->index;
                        $itemUrl = ($navService ?? app(\App\Services\NavigationService::class))->resolveUrl($item, $locale);
                        $isActive = isset($item['route']) && request()->routeIs(str_replace('.index', '.*', $item['route']));
                    @endphp
                    @if (! empty($item['mega']) && isset($featuredServices) && $featuredServices->isNotEmpty())
                        <div class="relative" @mouseenter="megaOpen = '{{ $itemKey }}'" @mouseleave="megaOpen = null">
                            <button
                                type="button"
                                @click="toggleMega('{{ $itemKey }}')"
                                :aria-expanded="megaOpen === '{{ $itemKey }}'"
                                class="nav-link flex items-center gap-1 {{ $isActive ? 'nav-link-active' : '' }}"
                            >
                                {{ $item['label'] }}
                                <svg class="h-4 w-4 transition-transform duration-300" :class="megaOpen === '{{ $itemKey }}' && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>

                            <div
                                x-show="megaOpen === '{{ $itemKey }}'"
                                x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0 translate-y-3 scale-[0.98]"
                                x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                                x-transition:leave="transition ease-in duration-200"
                                x-transition:leave-start="opacity-100"
                                x-transition:leave-end="opacity-0 translate-y-2"
                                class="absolute start-1/2 top-full z-50 mt-3 w-[min(56rem,calc(100vw-2rem))] -translate-x-1/2 rtl:translate-x-1/2"
                                x-cloak
                            >
                                <div class="mega-panel">
                                    <div class="grid gap-8 lg:grid-cols-12">
                                        <div class="lg:col-span-4 border-b border-white/10 pb-6 lg:border-b-0 lg:border-e lg:pb-0 lg:pe-8">
                                            <p class="text-overline text-accent">{{ __('navigation.capabilities') }}</p>
                                            <p class="mt-3 text-sm leading-relaxed text-steel-400">
                                                {{ __('navigation.mega_intro') }}
                                            </p>
                                            <x-ui.button :href="route('services.index', $locale)" variant="outline" size="sm" class="mt-6">
                                                {{ __('navigation.all_services') }}
                                            </x-ui.button>
                                        </div>
                                        <div class="lg:col-span-8">
                                            <div class="grid gap-2 sm:grid-cols-2">
                                                @foreach ($featuredServices->take(6) as $service)
                                                    @php $st = $service->translate($locale); @endphp
                                                    @if ($st)
                                                        <a href="{{ route('services.show', [$locale, $st->slug]) }}"
                                                           class="group rounded-xl border border-transparent p-4 transition duration-300 hover:border-white/10 hover:bg-white/5">
                                                            <p class="font-semibold text-white transition group-hover:text-accent">{{ $st->title }}</p>
                                                            @if ($st->summary)
                                                                <p class="mt-1 line-clamp-2 text-caption text-steel-500">{{ $st->summary }}</p>
                                                            @endif
                                                        </a>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <a href="{{ $itemUrl }}"
                           class="nav-link {{ $isActive ? 'nav-link-active' : '' }}">
                            {{ $item['label'] }}
                        </a>
                    @endif
                @endforeach
            </nav>

            <div class="flex items-center gap-2">
                @if (Route::has($switchRoute))
                    <a href="{{ route($switchRoute, $switchParams) }}"
                       class="hidden rounded-lg border border-white/15 px-3 py-2 text-caption font-semibold uppercase text-steel-400 transition hover:border-accent hover:text-white md:inline-flex"
                       hreflang="{{ $otherLocale }}">
                        {{ $otherLocale === 'ar' ? __('common.locale_ar') : __('common.locale_en') }}
                    </a>
                @endif
                <x-ui.button :href="route('contact', $locale)" size="sm" class="hidden sm:inline-flex">
                    {{ __('navigation.contact') }}
                </x-ui.button>
                <button type="button" class="rounded-xl p-2.5 text-steel-300 ring-1 ring-white/10 transition hover:bg-white/10 hover:text-white lg:hidden" @click="open = !open" :aria-expanded="open">
                    <span class="sr-only">{{ __('navigation.menu') }}</span>
                    <svg x-show="!open" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    <svg x-show="open" x-cloak class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>
    </div>

    <div x-show="open" x-transition.opacity class="fixed inset-0 z-40 bg-navy-950/95 backdrop-blur-md lg:hidden" x-cloak @click="open = false"></div>

    <nav
        x-show="open"
        x-transition:enter="transition ease-out duration-400"
        x-transition:enter-start="translate-x-full rtl:-translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-in duration-250"
        x-transition:leave-end="translate-x-full rtl:-translate-x-full"
        class="fixed inset-y-0 end-0 z-50 flex w-full max-w-[min(100%,24rem)] flex-col glass-panel-elevated lg:hidden"
        x-cloak
        aria-label="{{ __('navigation.mobile') }}"
    >
        <div class="flex items-center justify-between border-b border-white/10 p-5">
            <span class="font-bold text-white">{{ __('navigation.menu') }}</span>
            <button type="button" @click="open = false" class="rounded-lg p-2 text-steel-400 hover:text-white">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <div class="flex-1 overflow-y-auto p-5">
            <a href="{{ route('home', $locale) }}" @click="open=false" class="mb-1 block rounded-xl px-4 py-3.5 text-base font-medium text-white hover:bg-white/5">{{ __('navigation.home') }}</a>

            @foreach ($nav as $item)
                @php
                    $itemKey = $item['key'] ?? 'nav-'.$loop->index;
                    $itemUrl = ($navService ?? app(\App\Services\NavigationService::class))->resolveUrl($item, $locale);
                @endphp
                @if (! empty($item['mega']) && isset($featuredServices) && $featuredServices->isNotEmpty())
                    <button type="button" @click="mobileServices = !mobileServices" class="flex w-full items-center justify-between rounded-xl px-4 py-3.5 text-base font-medium text-steel-200 hover:bg-white/5">
                        {{ $item['label'] }}
                        <svg class="h-5 w-5 transition-transform" :class="mobileServices && 'rotate-180'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="mobileServices" x-transition class="ms-2 space-y-1 border-s border-white/10 ps-4">
                        @foreach ($featuredServices->take(5) as $service)
                            @php $st = $service->translate($locale); @endphp
                            @if ($st)
                                <a href="{{ route('services.show', [$locale, $st->slug]) }}" @click="open=false" class="block rounded-lg px-3 py-2.5 text-sm text-steel-400 hover:text-accent">{{ $st->title }}</a>
                            @endif
                        @endforeach
                        <a href="{{ route('services.index', $locale) }}" @click="open=false" class="block rounded-lg px-3 py-2.5 text-sm font-medium text-accent">{{ __('navigation.all_services') }}</a>
                    </div>
                @else
                    <a href="{{ $itemUrl }}" @click="open=false" class="block rounded-xl px-4 py-3.5 text-base text-steel-200 hover:bg-white/5">{{ $item['label'] }}</a>
                @endif
            @endforeach
        </div>

        <div class="border-t border-white/10 p-5 space-y-3">
            <x-ui.button :href="route('contact', $locale)" class="w-full justify-center" @click="open=false">{{ __('buttons.contact_us') }}</x-ui.button>
            @if (Route::has($switchRoute))
                <a href="{{ route($switchRoute, $switchParams) }}" class="block text-center text-sm text-accent">{{ $otherLocale === 'ar' ? __('common.locale_ar') : __('common.locale_en') }}</a>
            @endif
        </div>
    </nav>
</header>

<div class="h-16 lg:h-[4.75rem]" aria-hidden="true"></div>
