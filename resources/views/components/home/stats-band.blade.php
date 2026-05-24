@php
    use App\Support\LocaleHelper;
@endphp

<section id="stats" class="relative overflow-hidden section-padding-sm" x-data="statCounter">
    <div class="absolute inset-0 bg-industrial-grid-fine opacity-30" aria-hidden="true"></div>
    <div class="absolute inset-0 bg-gradient-to-r from-navy-950 via-navy-900/90 to-navy-950" aria-hidden="true"></div>

    <div class="container-iks relative">
        <div class="grid gap-8 sm:grid-cols-2 lg:grid-cols-4">
            @foreach (LocaleHelper::homeStats() as $i => $stat)
                <div class="reveal reveal-stagger-{{ $i + 1 }} text-center lg:text-start glass-panel rounded-2xl p-8 hover-shine card-premium border-0">
                    <p class="stat-value" data-count="{{ $stat['count'] }}" data-suffix="{{ $stat['suffix'] }}">0{{ $stat['suffix'] }}</p>
                    <p class="mt-3 text-sm font-medium text-steel-400">
                        {{ __('home.stats.'.$stat['label']) }}
                    </p>
                </div>
            @endforeach
        </div>
    </div>
</section>
