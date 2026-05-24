@props(['project', 'class' => ''])

@php
    $locale = app()->getLocale();
    $url = $project->featured_image_url;
@endphp

<div {{ $attributes->merge(['class' => 'relative overflow-hidden rounded-xl bg-navy-800 ring-1 ring-white/10 '.$class]) }}>
    @if ($url)
        <img
            src="{{ $url }}"
            alt="{{ $project->translate($locale)?->title ?? '' }}"
            class="h-full w-full object-cover transition duration-500 group-hover:scale-105"
            loading="lazy"
        >
        <div class="absolute inset-0 bg-gradient-to-t from-navy-950/60 to-transparent" aria-hidden="true"></div>
    @else
        <div class="flex h-full min-h-[12rem] w-full items-center justify-center bg-gradient-to-br from-navy-800 to-navy-900">
            <svg class="h-16 w-16 text-steel-500/40" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
        </div>
    @endif
</div>
