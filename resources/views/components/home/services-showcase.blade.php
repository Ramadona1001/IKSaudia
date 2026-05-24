@props(['services', 'translation' => null])

@php $locale = app()->getLocale(); @endphp

@if ($services->isNotEmpty())
<section class="section-padding section-divider bg-navy-900/30" x-data="servicesTabs" aria-labelledby="services-heading">
    <div class="container-iks">
        <div class="flex flex-col gap-8 lg:flex-row lg:items-end lg:justify-between reveal">
            <x-ui.section-heading
                :overline="__('home.services.overline')"
                :title="$translation?->title ?? __('navigation.services')"
                :subtitle="$translation?->subtitle ?? __('home.services.default_subtitle')"
            />
            <x-ui.button :href="route('services.index', $locale)" variant="ghost" class="shrink-0">
                {{ __('common.view_all_arrow') }}
            </x-ui.button>
        </div>

        <div class="mt-14 grid gap-8 lg:grid-cols-12 reveal">
            <div class="lg:col-span-4 flex flex-col gap-2" role="tablist">
                @foreach ($services as $index => $service)
                    @php $st = $service->translate($locale); @endphp
                    @if ($st)
                        <button
                            type="button"
                            role="tab"
                            :aria-selected="active === {{ $index }}"
                            @click="setActive({{ $index }})"
                            class="group rounded-2xl border px-5 py-5 text-start transition-all duration-300"
                            :class="active === {{ $index }} ? 'border-accent/60 bg-accent/10 shadow-glow-sm' : 'border-white/10 hover:border-white/20 hover:bg-white/5'"
                        >
                            <span class="text-overline" :class="active === {{ $index }} ? 'text-accent' : 'text-steel-500'">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                            <span class="mt-2 block font-semibold text-white">{{ $st->title }}</span>
                        </button>
                    @endif
                @endforeach
            </div>

            <div class="lg:col-span-8 min-h-[360px]">
                @foreach ($services as $index => $service)
                    @php $st = $service->translate($locale); @endphp
                    @if ($st)
                        <div
                            x-show="active === {{ $index }}"
                            x-transition:enter="transition ease-out duration-400"
                            x-transition:enter-start="opacity-0 translate-y-4"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            class="glass-panel-elevated h-full rounded-3xl p-8 lg:p-12 flex flex-col justify-between"
                            role="tabpanel"
                            x-cloak
                        >
                            <div>
                                <h3 id="services-heading" class="text-display-md text-white">{{ $st->title }}</h3>
                                <p class="mt-5 text-lead max-w-2xl">{{ $st->summary }}</p>
                            </div>
                            <x-ui.button :href="route('services.show', [$locale, $st->slug])" class="mt-10 self-start hover-shine">
                                {{ __('buttons.service_details') }}
                                <svg class="h-4 w-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                            </x-ui.button>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>
</section>
@endif
