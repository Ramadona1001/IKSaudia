@php
    use App\Support\LocaleHelper;
    $locale = app()->getLocale();
@endphp

<section id="process" class="section-padding bg-navy-900/40 section-divider" x-data="processTimeline">
    <div class="container-iks">
        <div class="mx-auto max-w-3xl text-center reveal">
            <x-ui.section-heading
                align="center"
                :overline="__('home.process.overline')"
                :title="__('home.process.title')"
                :subtitle="__('home.process.subtitle')"
            />
        </div>

        <div class="relative mt-20">
            <div class="timeline-line hidden lg:block" aria-hidden="true"></div>

            <div class="space-y-12 lg:space-y-0">
                @foreach (LocaleHelper::processStepKeys() as $i => $stepKey)
                    <div
                        class="reveal reveal-stagger-{{ min($i + 1, 6) }} relative flex flex-col gap-6 lg:grid lg:grid-cols-2 lg:gap-16 {{ $i % 2 === 1 ? 'lg:direction-rtl' : '' }}"
                        @click="setActive({{ $i }})"
                    >
                        <div class="{{ $i % 2 === 1 ? 'lg:col-start-2 lg:text-end' : 'lg:col-start-1' }} flex gap-5 lg:gap-0 lg:justify-end {{ $i % 2 === 1 ? 'lg:flex-row-reverse' : '' }}">
                            <div class="timeline-dot lg:absolute lg:left-1/2 lg:-translate-x-1/2 z-10 {{ $i % 2 === 1 ? 'lg:rtl:translate-x-1/2' : '' }}">
                                {{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}
                            </div>
                            <div class="flex-1 lg:max-w-md {{ $i % 2 === 0 ? 'lg:pe-16 lg:text-end' : 'lg:ps-16' }}">
                                <h3 class="text-xl font-bold text-white transition-colors" :class="active === {{ $i }} && 'text-accent'">
                                    {{ __('home.process.steps.'.$stepKey.'.title') }}
                                </h3>
                                <p class="mt-2 text-sm text-steel-400 leading-relaxed">
                                    {{ __('home.process.steps.'.$stepKey.'.description') }}
                                </p>
                            </div>
                        </div>
                        <div class="hidden lg:block {{ $i % 2 === 0 ? 'lg:col-start-2' : 'lg:col-start-1' }}"></div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
