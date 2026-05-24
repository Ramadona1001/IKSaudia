@props([
    'variant' => 'default',
    'showText' => true,
])

@php
    $logoKey = match ($variant) {
        'sticky' => 'general.logo_sticky',
        'footer' => 'general.logo_footer',
        'dark' => 'general.logo_dark',
        default => 'general.logo',
    };

    $logoUrl = setting_url($logoKey) ?? setting_url('general.logo');
    $name = setting('general.site_name') ?: __('common.app_name_short');
    $tagline = setting('general.site_tagline') ?: __('common.app_tagline');
@endphp

<span {{ $attributes->class('inline-flex items-center gap-3') }}>
    @if ($logoUrl)
        <img src="{{ $logoUrl }}" alt="{{ $name }}" class="h-11 w-auto max-w-[10rem] object-contain" loading="lazy" />
    @else
        <span class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-accent/15 ring-1 ring-accent/30">
            <svg class="h-6 w-6 text-accent" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v18M3 12h18"/>
            </svg>
        </span>
    @endif
    @if ($showText)
        <span class="hidden sm:block">
            <span class="block text-sm font-bold tracking-wide text-white">{{ $name }}</span>
            <span class="block text-caption text-steel-500">{{ $tagline }}</span>
        </span>
    @endif
</span>
