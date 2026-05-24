@extends('layouts.app')

@php $locale = app()->getLocale(); @endphp

<x-seo :seo="$seo" :fallback-title="$translation?->title" :fallback-description="$translation?->summary" />

@section('content')
    <x-ui.page-hero
        compact
        :title="$translation?->title"
        :subtitle="$translation?->summary"
    >
        <x-slot:breadcrumb>
            <x-ui.breadcrumb :items="[
                ['label' => __('navigation.services'), 'url' => route('services.index', $locale)],
                ['label' => $translation?->title ?? ''],
            ]" />
        </x-slot:breadcrumb>
    </x-ui.page-hero>

    <section class="section-padding">
        <div class="container-iks">
            <div class="grid gap-12 lg:grid-cols-12 reveal">
                <div class="lg:col-span-8">
                    <div class="prose-iks max-w-none text-base lg:text-lg">
                        {!! $translation?->body !!}
                    </div>
                </div>

                <aside class="lg:col-span-4">
                    <div class="glass-panel rounded-2xl p-6 lg:sticky lg:top-32 space-y-6">
                        @if ($service->industries->isNotEmpty())
                            <div>
                                <p class="text-overline text-accent mb-3">{{ __('services.show.industries') }}</p>
                                <ul class="flex flex-wrap gap-2">
                                    @foreach ($service->industries as $industry)
                                        @php $it = $industry->translate($locale); @endphp
                                        @if ($it)
                                            <li><x-ui.badge variant="steel">{{ $it->title }}</x-ui.badge></li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <x-ui.button :href="route('contact', $locale)" class="w-full justify-center">
                            {{ __('buttons.request_consultation') }}
                        </x-ui.button>
                        <x-ui.button :href="route('services.index', $locale)" variant="ghost" class="w-full justify-center">
                            {{ __('buttons.all_services') }}
                        </x-ui.button>
                    </div>
                </aside>
            </div>
        </div>
    </section>

    <x-ui.cta-strip />
@endsection
