@props([
    'title' => null,
    'subtitle' => null,
])

@php $locale = app()->getLocale(); @endphp

<section class="section-padding-sm border-t border-white/10">
    <div class="container-iks reveal">
        <div class="glass-panel flex flex-col items-center gap-8 rounded-2xl p-8 text-center lg:flex-row lg:justify-between lg:p-12 lg:text-start">
            <div class="max-w-xl">
                <p class="text-overline text-accent">{{ __('common.get_started') }}</p>
                <h2 class="mt-2 text-display-md text-white">
                    {{ $title ?? __('common.have_project') }}
                </h2>
                @if ($subtitle)
                    <p class="mt-3 text-steel-300">{{ $subtitle }}</p>
                @endif
            </div>
            <div class="flex flex-col gap-3 sm:flex-row shrink-0">
                <x-ui.button :href="route('contact', $locale)" size="lg">
                    {{ __('buttons.contact_us') }}
                </x-ui.button>
                <x-ui.button :href="route('services.index', $locale)" variant="secondary" size="lg">
                    {{ __('footer.our_services') }}
                </x-ui.button>
            </div>
        </div>
    </div>
</section>
