@extends('layouts.app')

@php $locale = app()->getLocale(); @endphp

@section('title', __('services.meta_title') . ' — ' . config('app.name'))

@section('content')
    <x-ui.page-hero
        :overline="__('services.index.overline')"
        :title="__('services.index.title')"
        :subtitle="__('services.index.subtitle')"
    />

    <section class="section-padding">
        <div class="container-iks">
            <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                @forelse ($services as $service)
                    @php $t = $service->translate($locale); @endphp
                    @if ($t)
                        <article class="reveal">
                            <x-ui.card :href="route('services.show', [$locale, $t->slug])" class="h-full group">
                                <span class="mb-4 inline-flex h-12 w-12 items-center justify-center rounded-lg bg-accent/10 text-accent ring-1 ring-accent/20">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                    </svg>
                                </span>
                                <h2 class="text-xl font-semibold text-white group-hover:text-accent transition">{{ $t->title }}</h2>
                                @if ($t->summary)
                                    <p class="mt-3 text-sm leading-relaxed text-steel-400 line-clamp-3">{{ $t->summary }}</p>
                                @endif
                                <span class="mt-6 inline-flex text-sm font-medium text-accent">
                                    {{ __('services.index.read_more') }}
                                </span>
                            </x-ui.card>
                        </article>
                    @endif
                @empty
                    <p class="col-span-full text-center text-steel-400 py-12">{{ __('services.index.empty') }}</p>
                @endforelse
            </div>

            @if ($services->hasPages())
                <div class="mt-12 reveal">{{ $services->links() }}</div>
            @endif
        </div>
    </section>

    <x-ui.cta-strip
        :title="__('services.cta.title')"
        :subtitle="__('services.cta.subtitle')"
    />
@endsection
