@php
    $locale = app()->getLocale();
    $otherLocale = $locale === 'ar' ? 'en' : 'ar';
    $switchParams = array_merge(request()->route()?->parameters() ?? [], ['locale' => $otherLocale]);
    $switchRoute = request()->route()?->getName() ?? 'home';
@endphp
<header class="sticky top-0 z-50 border-b border-white/10 bg-brand-950/90 backdrop-blur-md" x-data="{ open: false }">
    <div class="mx-auto flex max-w-7xl items-center justify-between gap-6 px-4 py-4 lg:px-8">
        <a href="{{ route('home', $locale) }}" class="flex items-center gap-3">
            <span class="flex h-10 w-10 items-center justify-center rounded-lg bg-brand-accent/20 text-sm font-bold text-brand-accent">IK</span>
            <span class="hidden text-sm font-semibold tracking-wide text-white sm:block">
                {{ __('common.app_name') }}
            </span>
        </a>

        <nav class="hidden items-center gap-8 text-sm font-medium text-brand-steel md:flex">
            <a href="{{ route('home', $locale) }}" @class(['text-white' => request()->routeIs('home')]) class="transition hover:text-white">{{ __('navigation.home') }}</a>
            <a href="{{ route('page.show', [$locale, 'about-us']) }}" @class(['text-white' => request()->is("{$locale}/about-us")]) class="transition hover:text-white">{{ __('navigation.about') }}</a>
            <a href="{{ route('services.index', $locale) }}" @class(['text-white' => request()->routeIs('services.*')]) class="transition hover:text-white">{{ __('navigation.services') }}</a>
            <a href="{{ route('contact', $locale) }}" @class(['text-white' => request()->routeIs('contact')]) class="transition hover:text-white">{{ __('navigation.contact') }}</a>
        </nav>

        <div class="flex items-center gap-3">
            @if (Route::has($switchRoute))
                <a href="{{ route($switchRoute, $switchParams) }}"
                   class="hidden rounded-md border border-white/15 px-3 py-1.5 text-xs font-medium uppercase tracking-wider text-brand-steel transition hover:border-brand-accent hover:text-white sm:inline-block">
                    {{ $otherLocale === 'ar' ? __('common.locale_ar') : __('common.locale_en') }}
                </a>
            @endif
            <button type="button" class="md:hidden text-brand-steel hover:text-white" @click="open = !open" aria-label="Menu">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
        </div>
    </div>

    <nav x-show="open" x-cloak class="border-t border-white/10 px-4 py-4 md:hidden">
        <div class="flex flex-col gap-3 text-sm font-medium text-brand-steel">
            <a href="{{ route('home', $locale) }}" class="hover:text-white">{{ __('navigation.home') }}</a>
            <a href="{{ route('page.show', [$locale, 'about-us']) }}" class="hover:text-white">{{ __('navigation.about') }}</a>
            <a href="{{ route('services.index', $locale) }}" class="hover:text-white">{{ __('navigation.services') }}</a>
            <a href="{{ route('contact', $locale) }}" class="hover:text-white">{{ __('navigation.contact') }}</a>
            <a href="{{ route($switchRoute, $switchParams) }}" class="text-brand-accent">{{ $otherLocale === 'ar' ? __('common.locale_ar') : __('common.locale_en') }}</a>
        </div>
    </nav>
</header>
