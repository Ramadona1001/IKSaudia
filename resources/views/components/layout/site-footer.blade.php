@php
    $locale = app()->getLocale();
    $year = date('Y');
    $footerCtaEnabled = setting('footer.cta_enabled', true);
    $legalLinks = setting('footer.legal_links', []);
    $certBadges = $siteSettings->certificationBadges();
@endphp

<footer class="relative mt-auto border-t border-white/10 bg-navy-900" role="contentinfo">
    <div class="absolute inset-0 bg-industrial-grid opacity-20 pointer-events-none" aria-hidden="true"></div>

    @if ($footerCtaEnabled)
        <div class="relative border-b border-white/10 overflow-hidden">
            @if ($footerBg = setting_url('footer.background_image'))
                <img src="{{ $footerBg }}" alt="" class="absolute inset-0 h-full w-full object-cover opacity-20" aria-hidden="true" />
            @endif
            <div class="absolute inset-0 bg-gradient-to-br from-navy-800/80 via-navy-900 to-navy-950" aria-hidden="true"></div>
            <div class="absolute -end-32 top-0 h-64 w-64 rounded-full bg-accent/10 blur-3xl" aria-hidden="true"></div>

            <div class="container-iks section-padding-sm relative">
                <div class="flex flex-col items-center gap-10 text-center lg:flex-row lg:justify-between lg:text-start reveal-scale">
                    <div class="max-w-xl">
                        <p class="text-overline text-accent">{{ setting('footer.cta_overline') ?: __('footer.cta.overline') }}</p>
                        <h2 class="mt-3 text-display-lg text-white">
                            {{ setting('footer.cta_title') ?: __('footer.cta.title') }}
                        </h2>
                        <p class="mt-4 text-lead">
                            {{ setting('footer.cta_subtitle') ?: __('footer.cta.subtitle') }}
                        </p>
                    </div>
                    <div class="flex flex-col gap-3 sm:flex-row shrink-0">
                        <x-ui.button :href="route('contact', $locale)" size="lg" class="hover-shine min-w-[180px] justify-center">
                            {{ __('buttons.contact_us') }}
                        </x-ui.button>
                        <x-ui.button :href="route('services.index', $locale)" variant="secondary" size="lg" class="min-w-[180px] justify-center">
                            {{ __('footer.our_services') }}
                        </x-ui.button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="container-iks relative py-16 lg:py-24">
        <div class="grid gap-14 lg:grid-cols-12">
            <div class="lg:col-span-4">
                <a href="{{ route('home', $locale) }}" class="inline-flex items-center gap-3 group">
                    <x-layout.site-logo variant="footer" />
                </a>
                <p class="mt-5 max-w-sm text-sm leading-relaxed text-steel-400">
                    {{ setting('footer.description') ?: __('footer.tagline') }}
                </p>
                <div class="mt-8 flex flex-wrap gap-2">
                    @forelse ($certBadges as $cert)
                        @if ($cert['enabled'] ?? true)
                            <span class="rounded-lg border border-white/10 bg-navy-950/60 px-3 py-1.5 text-caption font-medium text-steel-400">{{ $cert['code'] ?? $cert['label'] }}</span>
                        @endif
                    @empty
                        @foreach (['ISO', 'ASME', 'API', 'ASTM'] as $cert)
                            <span class="rounded-lg border border-white/10 bg-navy-950/60 px-3 py-1.5 text-caption font-medium text-steel-400">{{ $cert }}</span>
                        @endforeach
                    @endforelse
                </div>
                @if ($socialLinks = $siteSettings->socialLinks())
                    <div class="mt-8 flex flex-wrap gap-3">
                        @foreach ($socialLinks as $social)
                            <a href="{{ $social['url'] }}" target="_blank" rel="noopener noreferrer" class="rounded-lg border border-white/10 px-3 py-2 text-caption text-steel-400 transition hover:border-accent hover:text-accent">
                                {{ $social['label'] ?? ucfirst($social['platform'] ?? '') }}
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

            <div class="grid grid-cols-2 gap-10 sm:grid-cols-3 lg:col-span-5">
                @php
                    $quickLinks = setting('footer.quick_links', []);
                @endphp
                @if (count($quickLinks) > 0)
                    <div>
                        <p class="text-overline text-accent mb-5">{{ __('common.company') }}</p>
                        <ul class="space-y-3.5 text-sm text-steel-400">
                            @foreach ($quickLinks as $link)
                                @if ($link['is_visible'] ?? true)
                                    @php $label = $locale === 'ar' ? ($link['label_ar'] ?? $link['label_en']) : ($link['label_en'] ?? $link['label_ar']); @endphp
                                    <li><a href="{{ $link['url'] }}" class="transition hover:text-white hover:ps-1">{{ $label }}</a></li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                @else
                    <div>
                        <p class="text-overline text-accent mb-5">{{ __('common.company') }}</p>
                        <ul class="space-y-3.5 text-sm text-steel-400">
                            <li><a href="{{ route('page.show', [$locale, 'about-us']) }}" class="transition hover:text-white hover:ps-1">{{ __('navigation.about') }}</a></li>
                            <li><a href="{{ route('services.index', $locale) }}" class="transition hover:text-white hover:ps-1">{{ __('navigation.services') }}</a></li>
                            <li><a href="{{ route('projects.index', $locale) }}" class="transition hover:text-white hover:ps-1">{{ __('navigation.projects') }}</a></li>
                            <li><a href="#process" class="transition hover:text-white hover:ps-1">{{ __('navigation.process') }}</a></li>
                            <li><a href="{{ route('contact', $locale) }}" class="transition hover:text-white hover:ps-1">{{ __('navigation.contact') }}</a></li>
                        </ul>
                    </div>
                @endif
                <div>
                    <p class="text-overline text-accent mb-5">{{ __('common.sectors') }}</p>
                    <ul class="space-y-3.5 text-sm text-steel-400">
                        @foreach (setting('footer.industry_links', []) as $link)
                            @if ($link['is_visible'] ?? true)
                                @php $label = $locale === 'ar' ? ($link['label_ar'] ?? $link['label_en']) : ($link['label_en'] ?? $link['label_ar']); @endphp
                                <li><a href="{{ $link['url'] }}" class="hover:text-white transition">{{ $label }}</a></li>
                            @endif
                        @endforeach
                    </ul>
                </div>
                <div class="col-span-2 sm:col-span-1">
                    <p class="text-overline text-accent mb-5">{{ __('navigation.contact') }}</p>
                    <ul class="space-y-3.5 text-sm">
                        @foreach ($siteSettings->phones() as $phone)
                            <li><a href="tel:{{ preg_replace('/\s+/', '', $phone['number'] ?? '') }}" class="text-steel-300 transition hover:text-accent">{{ $phone['number'] }}</a></li>
                        @endforeach
                        @foreach ($siteSettings->emails() as $email)
                            <li><a href="mailto:{{ $email['address'] }}" class="text-steel-300 transition hover:text-accent">{{ $email['address'] }}</a></li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="lg:col-span-3">
                <p class="text-overline text-accent mb-5">{{ __('footer.address') }}</p>
                <address class="not-italic text-sm leading-relaxed text-steel-400 whitespace-pre-line">{{ setting('contact.address') ?: __('common.address_multiline') }}</address>
            </div>
        </div>
    </div>

    <div class="relative border-t border-white/10 bg-navy-950">
        <div class="container-iks flex flex-col items-center justify-between gap-4 py-6 text-caption text-steel-500 sm:flex-row">
            <p>&copy; {{ $year }} {{ setting('footer.copyright') ?: __('footer.copyright', ['year' => $year]) }} {{ __('common.all_rights_reserved') }}</p>
            <div class="flex gap-8">
                @forelse ($legalLinks as $link)
                    @if ($link['is_visible'] ?? true)
                        @php $label = $locale === 'ar' ? ($link['label_ar'] ?? $link['label_en']) : ($link['label_en'] ?? $link['label_ar']); @endphp
                        <a href="{{ $link['url'] }}" class="transition hover:text-steel-300">{{ $label }}</a>
                    @endif
                @empty
                    <a href="#" class="transition hover:text-steel-300">{{ __('common.privacy') }}</a>
                    <a href="#" class="transition hover:text-steel-300">{{ __('common.terms') }}</a>
                @endforelse
            </div>
        </div>
    </div>
</footer>
