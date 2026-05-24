@props(['industries'])

@php
    use App\Support\LocaleHelper;
    $locale = app()->getLocale();
    $industryIcons = [
        'oil_gas' => 'oil-gas',
        'mining' => 'mining',
        'subsea' => 'subsea',
        'petrochemicals' => 'petrochemical',
    ];
@endphp

<section id="industries" class="section-padding section-divider relative overflow-hidden">
    <x-home.industrial-background />
    <div class="container-iks relative z-10">
        <div class="flex flex-col gap-8 lg:flex-row lg:items-end lg:justify-between reveal">
            <x-ui.section-heading
                :overline="__('home.industries.overline')"
                :title="__('home.industries.title')"
                :subtitle="__('home.industries.subtitle')"
            />
        </div>

        @if ($industries->isNotEmpty())
            <div class="mt-14 grid gap-6 sm:grid-cols-2 lg:grid-cols-4 reveal">
                @foreach ($industries as $i => $industry)
                    @php $it = $industry->translate($locale); @endphp
                    @if ($it)
                        <article class="card-premium group p-8 hover-shine reveal-stagger-{{ min($i + 1, 6) }}">
                            <div class="mb-6 flex h-14 w-14 items-center justify-center rounded-2xl bg-accent/10 ring-1 ring-accent/20 transition duration-300 group-hover:shadow-glow-sm">
                                <x-ui.industry-icon :icon="$industry->icon ?? 'default'" />
                            </div>
                            <h3 class="text-lg font-bold text-white transition group-hover:text-accent">{{ $it->title }}</h3>
                            @if ($it->summary)
                                <p class="mt-3 text-sm leading-relaxed text-steel-400">{{ $it->summary }}</p>
                            @endif
                            <a href="{{ route('services.index', $locale) }}" class="mt-6 inline-flex items-center gap-2 text-sm font-semibold text-accent opacity-0 transition duration-300 group-hover:opacity-100">
                                {{ __('buttons.related_services') }}
                                <svg class="h-4 w-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                            </a>
                        </article>
                    @endif
                @endforeach
            </div>
        @else
            <div class="mt-14 grid gap-6 sm:grid-cols-2 lg:grid-cols-4 reveal">
                @foreach (LocaleHelper::industryFallbackKeys() as $key)
                    <article class="card-premium p-8 text-center">
                        <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-accent/10">
                            <x-ui.industry-icon :icon="$industryIcons[$key]" />
                        </div>
                        <h3 class="font-bold text-white">{{ __('home.industries.fallback.'.$key) }}</h3>
                    </article>
                @endforeach
            </div>
        @endif
    </div>
</section>
