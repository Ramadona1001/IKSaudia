@extends('front.layouts.app')

@section('title', setting('seo.home_meta_title') ?: setting('general.site_name'))
@section('meta_description', setting('seo.home_meta_description') ?: __('footer.tagline'))

@push('seo')
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "Organization",
        "name": "{{ setting('general.site_name') ?: __('common.app_name') }}",
        "url": "{{ url('/') }}",
        "description": "{{ setting('seo.default_meta_description') ?: __('footer.tagline') }}",
        "address": {
            "@type": "PostalAddress",
            "addressCountry": "SA",
            "addressLocality": "{{ __('footer.location_city') }}"
        }
    }
    </script>
@endpush

@section('content')

    {{-- ============================================================
         SECTION 1 — HERO SLIDER (dynamic from admin → home-sections › hero)
         ============================================================ --}}
    @php
        $locale = app()->getLocale();
        $heroSection = ($sections ?? collect())->firstWhere('key', 'hero');
        $heroSlidesDb = $heroSection?->items?->filter(fn ($i) => $i->is_active) ?? collect();
        $heroSettings = is_array($heroSection?->settings ?? null) ? $heroSection->settings : [];
        $autoplay = (bool) ($heroSettings['autoplay'] ?? true);
        $autoplayInterval = (int) ($heroSettings['interval_ms'] ?? 6000);

        $heroSlides = $heroSlidesDb
            ->map(function ($item) use ($locale) {
                $t = $item->translate($locale);
                if (! $t) {
                    return null;
                }
                return [
                    'image_url' => $item->imageUrl(),
                    'title' => $t->title,
                    'subtitle' => $t->description,
                    'cta1_label' => $t->button_text,
                    'cta1_url' => $t->button_url,
                    'cta2_label' => $t->secondary_button_text,
                    'cta2_url' => $t->secondary_button_url,
                ];
            })
            ->filter()
            ->values();

        // Fallback static slides if no admin slides yet (so the hero never breaks).
        if ($heroSlides->isEmpty()) {
            $heroSlides = collect([
                [
                    'image_url' => null,
                    'class' => 'hero-slide-1',
                    'title' => __('front.hero.slide1.title1').' '.__('front.hero.slide1.title1_gold').' '.__('front.hero.slide1.title2').' '.__('front.hero.slide1.title2_blue'),
                    'subtitle' => __('front.hero.slide1.subtitle'),
                    'cta1_label' => __('front.hero.slide1.cta1'),
                    'cta1_url' => route('services.index', $locale),
                    'cta2_label' => __('front.hero.slide1.cta2'),
                    'cta2_url' => route('about', $locale),
                ],
                [
                    'image_url' => null,
                    'class' => 'hero-slide-2',
                    'title' => __('front.hero.slide2.title1_gold').' '.__('front.hero.slide2.title2').' '.__('front.hero.slide2.title2_blue'),
                    'subtitle' => __('front.hero.slide2.subtitle'),
                    'cta1_label' => __('front.hero.slide2.cta1'),
                    'cta1_url' => route('industries.index', $locale),
                    'cta2_label' => __('front.hero.slide2.cta2'),
                    'cta2_url' => route('contact', $locale),
                ],
                [
                    'image_url' => null,
                    'class' => 'hero-slide-3',
                    'title' => __('front.hero.slide3.title1').' '.__('front.hero.slide3.title1_gold').' '.__('front.hero.slide3.title2').' '.__('front.hero.slide3.title2_blue'),
                    'subtitle' => __('front.hero.slide3.subtitle'),
                    'cta1_label' => __('front.hero.slide3.cta1'),
                    'cta1_url' => route('clients', $locale),
                    'cta2_label' => __('front.hero.slide3.cta2'),
                    'cta2_url' => route('contact', $locale),
                ],
            ]);
        }
    @endphp

    <section
        id="hero"
        class="hero-section"
        aria-label="{{ __('common.hero') ?: 'Hero' }}"
        data-autoplay="{{ $autoplay ? 'true' : 'false' }}"
        data-autoplay-interval="{{ $autoplayInterval }}"
    >
        <div class="swiper hero-swiper">
            <div class="swiper-wrapper">
                @foreach ($heroSlides as $slide)
                    <div class="swiper-slide hero-slide {{ $slide['class'] ?? 'hero-slide-'.($loop->index + 1) }}"
                         @if (! empty($slide['image_url'])) style="background-image:url('{{ $slide['image_url'] }}');background-size:cover;background-position:center;" @endif>
                        @if (empty($slide['image_url']))
                            <div class="hero-particles" aria-hidden="true">
                                @for ($i = 0; $i < 10; $i++)<span class="hero-particle"></span>@endfor
                            </div>
                            <div class="hero-shapes" aria-hidden="true">
                                <div class="hero-shape hero-shape-1"></div>
                                <div class="hero-shape hero-shape-2"></div>
                                <div class="hero-shape hero-shape-3"></div>
                            </div>
                        @else
                            <div class="hero-overlay" aria-hidden="true" style="position:absolute;inset:0;background:linear-gradient(135deg, rgba(6,12,26,0.78) 0%, rgba(6,12,26,0.55) 60%, rgba(6,12,26,0.85) 100%);"></div>
                        @endif
                        <div class="hero-accent-line" aria-hidden="true"></div>

                        <div class="container">
                            <div class="hero-content">
                                @if (! empty($slide['title']))
                                    <h1 class="hero-title">{!! e($slide['title']) !!}</h1>
                                @endif
                                @if (! empty($slide['subtitle']))
                                    <p class="hero-subtitle">{{ $slide['subtitle'] }}</p>
                                @endif
                                @if (! empty($slide['cta1_label']) || ! empty($slide['cta2_label']))
                                    <div class="hero-cta">
                                        @if (! empty($slide['cta1_label']))
                                            <a href="{{ $slide['cta1_url'] ?: '#' }}" class="btn-gold">
                                                <i class="bi bi-arrow-right-circle-fill" aria-hidden="true"></i>
                                                <span>{{ $slide['cta1_label'] }}</span>
                                            </a>
                                        @endif
                                        @if (! empty($slide['cta2_label']))
                                            <a href="{{ $slide['cta2_url'] ?: '#' }}" class="btn-outline-light">
                                                <i class="bi bi-play-circle" aria-hidden="true"></i>
                                                <span>{{ $slide['cta2_label'] }}</span>
                                            </a>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if ($heroSlides->count() > 1)
                <div class="swiper-pagination" aria-label="Slide navigation"></div>
                <button class="swiper-button-prev" type="button" aria-label="{{ __('common.previous') }}"></button>
                <button class="swiper-button-next" type="button" aria-label="{{ __('common.next') }}"></button>
            @endif
        </div>

        <div class="scroll-indicator" aria-hidden="true" onclick="document.getElementById('services').scrollIntoView({behavior:'smooth'})">
            <span class="scroll-indicator-text">{{ __('front.hero.scroll') }}</span>
            <div class="scroll-indicator-line"></div>
        </div>
    </section>

    {{-- ============================================================
         SECTION 2 — SERVICES
         ============================================================ --}}
    <section id="services" class="services-section section-pad">
        <div class="container">
            <div class="services-header" data-aos="fade-up">
                <x-front.section-heading
                    :eyebrow="__('front.home.services.eyebrow')"
                    :title="__('front.home.services.title')"
                    :highlight="__('front.home.services.highlight')"
                    :description="__('front.home.services.desc')"
                />
            </div>

            <div class="row g-4">
                @forelse ($featuredServices as $service)
                    <div class="col-lg-4 col-md-6">
                        <x-front.service-card :service="$service" :index="$loop->iteration" :delay="$loop->index * 100" />
                    </div>
                @empty
                    <div class="col-12">
                        <p class="text-center" style="color:var(--c-muted);">{{ __('front.services.no_services') }}</p>
                    </div>
                @endforelse
            </div>

            <div class="text-center mt-5" data-aos="fade-up">
                <a href="{{ route('services.index', app()->getLocale()) }}" class="btn-outline-gold">
                    <span>{{ __('front.home.services.view_all') }}</span>
                    <i class="bi bi-arrow-right" aria-hidden="true"></i>
                </a>
            </div>
        </div>
    </section>

    <div class="section-divider"></div>

    {{-- ============================================================
         SECTION 3 — ABOUT US (CMS: home-sections › about_snippet)
         ============================================================ --}}
    @php
        $aboutSection = ($sections ?? collect())->firstWhere('key', 'about_snippet')
            ?? ($sections ?? collect())->firstWhere('type', 'about_snippet');
        $aboutT = $aboutSection?->translate($locale);
        $aboutImageUrl = $aboutSection?->featured_image_url;
        $aboutCtaUrl = $aboutT?->cta_url ?: route('about', $locale);
        $aboutCtaLabel = $aboutT?->cta_label ?: __('front.home.about.learn_more');
        $aboutSettings = is_array($aboutSection?->settings) ? $aboutSection->settings : [];
        $aboutStats = \App\Support\AboutSectionStats::forLocale($aboutSettings, $locale);
        $aboutYearsBadge = \App\Support\AboutSectionStats::yearsBadgeForLocale($aboutSettings, $locale);
    @endphp
    <section id="about" class="about-section section-pad">
        <div class="container">
            <div class="row g-5 align-items-center">

                <div class="col-lg-6" data-aos="fade-right">
                    <div class="about-visual">
                        <div class="about-img-main">
                            <div class="about-img-bg">
                                @if ($aboutImageUrl)
                                    <img src="{{ $aboutImageUrl }}" alt="{{ $aboutT?->title ?: __('front.home.about.title') }}" class="about-img-photo w-100 h-100 object-fit-cover">
                                @else
                                    <div class="about-img-graphic" aria-hidden="true">
                                        <i class="bi bi-gear-wide-connected about-img-icon"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="about-badge" aria-label="ISO Certified">
                                <div class="about-badge-icon"><i class="bi bi-patch-check-fill" aria-hidden="true"></i></div>
                                <div class="about-badge-text">
                                    <strong>{{ __('front.home.about.iso_label') }}</strong>
                                    <span>{{ __('front.home.about.iso_caption') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="about-img-float">
                            <div class="about-img-float-num" data-count="{{ $aboutYearsBadge['count'] }}" data-suffix="{{ $aboutYearsBadge['suffix'] }}">0{{ $aboutYearsBadge['suffix'] }}</div>
                            <div class="about-img-float-text">{{ $aboutYearsBadge['label'] }}</div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6" data-aos="fade-left">
                    <div class="about-content">
                        <div class="section-eyebrow">{{ $aboutT?->subtitle ?: __('front.home.about.eyebrow') }}</div>
                        <h2 class="section-title">
                            @if ($aboutT?->title)
                                <span>{{ $aboutT->title }}</span>
                            @else
                                <span>{{ __('front.home.about.title') }}</span>
                                <span class="accent">{{ __('front.home.about.highlight') }}</span>
                            @endif
                        </h2>
                        <p class="section-desc">{{ $aboutT?->bodyText() ?: __('front.home.about.desc') }}</p>

                        <div class="about-stats">
                            @foreach ($aboutStats as $stat)
                                <x-front.stat-counter
                                    :count="$stat['count']"
                                    :suffix="$stat['suffix']"
                                    :label="$stat['label']"
                                    :variant="$stat['variant']"
                                    :delay="$stat['delay']"
                                />
                            @endforeach
                        </div>

                        <a href="{{ $aboutCtaUrl }}" class="btn-gold">
                            <span>{{ $aboutCtaLabel }}</span>
                            <i class="bi bi-arrow-right" aria-hidden="true"></i>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <div class="section-divider"></div>

    {{-- ============================================================
         SECTION 3b — MISSION, VISION & VALUES
         ============================================================ --}}
    <section id="foundation" class="foundation-section section-pad">
        <div class="container">
            <x-front.section-heading
                :eyebrow="__('front.about.foundation_eyebrow')"
                :title="__('front.about.foundation_title')"
                :highlight="__('front.about.foundation_highlight')"
                data-aos="fade-up"
            />

            <div class="foundation-grid">
                <x-front.foundation-card
                    :title="__('front.home.about.mission_title')"
                    :description="__('front.home.about.mission_desc')"
                    icon="bi-bullseye"
                    variant="mission"
                    data-aos="fade-up"
                    data-aos-delay="0"
                />
                <x-front.foundation-card
                    :title="__('front.home.about.vision_title')"
                    :description="__('front.home.about.vision_desc')"
                    icon="bi-eye-fill"
                    variant="vision"
                    data-aos="fade-up"
                    data-aos-delay="100"
                />
                <x-front.foundation-card
                    :title="__('front.about.values_title')"
                    :description="__('front.about.values_desc')"
                    icon="bi-stars"
                    variant="values"
                    data-aos="fade-up"
                    data-aos-delay="200"
                />
            </div>
        </div>
    </section>

    <div class="section-divider"></div>

    {{-- ============================================================
         SECTION 4 — INDUSTRIES
         ============================================================ --}}
    @if ($featuredIndustries->isNotEmpty())
    <section id="industries" class="industries-section section-pad">
        <div class="container">
            <div class="industries-header" data-aos="fade-up">
                <x-front.section-heading
                    :eyebrow="__('front.home.industries.eyebrow')"
                    :title="__('front.home.industries.title')"
                    :highlight="__('front.home.industries.highlight')"
                    :description="__('front.home.industries.desc')"
                />
            </div>

            <div class="industries-slider" data-aos="fade-up" data-aos-delay="100">
                <div class="swiper industries-swiper">
                    <div class="swiper-wrapper">
                        @foreach ($featuredIndustries as $industry)
                            <div class="swiper-slide">
                                <x-front.industry-card :industry="$industry" :index="$loop->iteration" :delay="0" />
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="industries-swiper-nav" aria-label="{{ __('navigation.industries') }}">
                    <button class="ind-nav-btn ind-nav-prev" type="button" aria-label="{{ __('common.previous') }}">
                        <i class="bi bi-arrow-left" aria-hidden="true"></i>
                    </button>
                    <button class="ind-nav-btn ind-nav-next" type="button" aria-label="{{ __('common.next') }}">
                        <i class="bi bi-arrow-right" aria-hidden="true"></i>
                    </button>
                </div>
            </div>
        </div>
    </section>
    @endif

    <div class="section-divider"></div>

    {{-- ============================================================
         SECTION 5 — OUR CLIENTS
         ============================================================ --}}
    @if ($featuredClients->isNotEmpty() || true)
    <section id="clients" class="clients-section section-pad">
        <div class="container">
            <div class="clients-header" data-aos="fade-up">
                <x-front.section-heading
                    :eyebrow="__('front.home.clients.eyebrow')"
                    :title="__('front.home.clients.title')"
                    :highlight="__('front.home.clients.highlight')"
                    :description="__('front.home.clients.desc')"
                />
            </div>
        </div>

        @php
            $locale = app()->getLocale();
            $defaultClients = [
                ['name' => 'ARAMCO',  'icon' => 'bi-droplet-fill'],
                ['name' => 'SABIC',   'icon' => 'bi-flask-fill'],
                ['name' => 'SEC',     'icon' => 'bi-lightning-fill'],
                ['name' => 'NEOM',    'icon' => 'bi-building-fill'],
                ['name' => 'MAADEN',  'icon' => 'bi-gem'],
                ['name' => 'STC',     'icon' => 'bi-wifi'],
                ['name' => 'ACWA',    'icon' => 'bi-sun-fill'],
                ['name' => 'SBG',     'icon' => 'bi-hammer'],
                ['name' => 'SIPCHEM', 'icon' => 'bi-cpu-fill'],
                ['name' => 'TASNEE',  'icon' => 'bi-gear-fill'],
            ];

            $marqueeItems = $featuredClients->isNotEmpty()
                ? $featuredClients->map(function ($client) use ($locale) {
                    $translation = $client->translate($locale);

                    return [
                        'name' => $translation?->name,
                        'image' => $client->featured_image_url,
                        'url' => $client->website_url,
                    ];
                })->filter(fn (array $item) => filled($item['name']))->values()
                : collect($defaultClients)->map(fn (array $client) => [
                    'name' => $client['name'],
                    'image' => null,
                    'url' => null,
                ]);
        @endphp

        <div
            class="clients-marquee-wrap clients-marquee-wrap--standalone"
            role="region"
            aria-label="{{ __('front.home.clients.eyebrow') }}"
            data-aos="fade-up"
            data-aos-delay="100"
        >
            <div class="clients-marquee">
                @foreach ($marqueeItems as $item)
                    <x-front.client-marquee-item
                        :name="$item['name']"
                        :image="$item['image']"
                        :url="$item['url']"
                    />
                @endforeach
                @foreach ($marqueeItems as $item)
                    <x-front.client-marquee-item
                        :name="$item['name']"
                        :image="$item['image']"
                        :url="$item['url']"
                        aria-hidden="true"
                    />
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <div class="section-divider"></div>

    {{-- ============================================================
         SECTION 6 — PARTNERS
         ============================================================ --}}
    @if ($featuredPartners->isNotEmpty() || true)
    <section id="partners" class="partners-section section-pad">
        <div class="container">
            <div class="partners-header" data-aos="fade-up">
                <x-front.section-heading
                    :eyebrow="__('front.home.partners.eyebrow')"
                    :title="__('front.home.partners.title')"
                    :highlight="__('front.home.partners.highlight')"
                    :description="__('front.home.partners.desc')"
                />
            </div>

            <div class="partners-grid" data-aos="fade-up" data-aos-delay="100">
                @if ($featuredPartners->isNotEmpty())
                    @foreach ($featuredPartners as $partner)
                        @php $pt = $partner->translate(app()->getLocale()); @endphp
                        <x-front.partner-card
                            :name="$pt?->name ?? '—'"
                            :type="$pt?->description"
                            :url="$partner->website_url ?? '#'"
                            :image="$partner->featured_image_url"
                            icon="bi-cpu-fill"
                        />
                    @endforeach
                @else
                    @php
                        $defaultPartners = [
                            ['name' => 'SIEMENS', 'icon' => 'bi-cpu-fill', 'type' => 'Technology Partner'],
                            ['name' => 'ABB GROUP', 'icon' => 'bi-lightning-charge-fill', 'type' => 'Automation Partner'],
                            ['name' => 'HONEYWELL', 'icon' => 'bi-thermometer-half', 'type' => 'Process Partner'],
                            ['name' => 'CATERPILLAR', 'icon' => 'bi-truck', 'type' => 'Equipment Partner'],
                            ['name' => 'BUREAU VERITAS', 'icon' => 'bi-patch-check-fill', 'type' => 'Inspection Partner'],
                            ['name' => "LLOYD'S", 'icon' => 'bi-shield-fill-check', 'type' => 'Certification Body'],
                        ];
                    @endphp
                    @foreach ($defaultPartners as $p)
                        <x-front.partner-card :name="$p['name']" :type="$p['type']" :icon="$p['icon']" url="#" />
                    @endforeach
                @endif
            </div>
        </div>
    </section>
    @endif

    <div class="section-divider"></div>

    {{-- ============================================================
         SECTION 6.5 — FEATURED PROJECTS (admin-managed)
         ============================================================ --}}
    @if (isset($featuredProjects) && $featuredProjects->isNotEmpty())
        <section id="projects" class="projects-section section-pad bg-dark2">
            <div class="container">
                <div class="services-header" data-aos="fade-up">
                    <x-front.section-heading
                        :eyebrow="__('front.home.projects.eyebrow') ?: __('navigation.projects')"
                        :title="__('front.home.projects.title') ?: __('navigation.projects')"
                        :highlight="__('front.home.projects.highlight')"
                        :description="__('front.home.projects.desc')"
                    />
                </div>

                <div class="row g-4">
                    @foreach ($featuredProjects as $project)
                        @php $pt = $project->translate(app()->getLocale()); @endphp
                        @if ($pt)
                            <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ $loop->index * 80 }}">
                                <a href="{{ route('projects.show', [app()->getLocale(), $pt->slug]) }}" class="service-card" style="display:flex;flex-direction:column;height:100%;overflow:hidden;border-top:3px solid var(--c-gold);">
                                    @if ($project->featured_image_url)
                                        <div style="height:200px;background-image:url('{{ $project->featured_image_url }}');background-size:cover;background-position:center;"></div>
                                    @else
                                        <div style="height:200px;background:linear-gradient(135deg,#0d2137 0%,#1a3a5c 40%,#0a1020 100%);display:flex;align-items:center;justify-content:center;">
                                            <i class="bi bi-kanban-fill" style="font-size:4rem;color:rgba(201,162,39,0.15);" aria-hidden="true"></i>
                                        </div>
                                    @endif
                                    <div class="service-body">
                                        @if ($project->year)
                                            <div style="font-size:0.72rem;font-weight:700;letter-spacing:2px;text-transform:uppercase;color:var(--c-gold);margin-bottom:8px;">{{ $project->year }}@if ($project->location) · {{ $project->location }} @endif</div>
                                        @endif
                                        <h3 class="service-title">{{ $pt->title }}</h3>
                                        @if ($pt->summary)
                                            <p class="service-desc">{{ Str::limit($pt->summary, 110) }}</p>
                                        @endif
                                    </div>
                                </a>
                            </div>
                        @endif
                    @endforeach
                </div>

                <div class="text-center mt-5" data-aos="fade-up">
                    <a href="{{ route('projects.index', app()->getLocale()) }}" class="btn-outline-gold">
                        <span>{{ __('navigation.projects') }}</span>
                        <i class="bi bi-arrow-right" aria-hidden="true"></i>
                    </a>
                </div>
            </div>
        </section>

        <div class="section-divider"></div>
    @endif

    {{-- ============================================================
         SECTION 6.6 — CERTIFICATIONS (admin-managed)
         ============================================================ --}}
    @if (isset($featuredCertifications) && $featuredCertifications->isNotEmpty())
        <section id="certifications" class="certifications-section section-pad">
            <div class="container">
                <div class="industries-header" data-aos="fade-up">
                    <x-front.section-heading
                        :eyebrow="__('front.home.certs.eyebrow') ?: __('navigation.certifications')"
                        :title="__('front.home.certs.title') ?: __('common.certifications')"
                        :highlight="__('front.home.certs.highlight')"
                        :description="__('front.home.certs.desc')"
                    />
                </div>

                <div class="clients-logo-grid" data-aos="fade-up" data-aos-delay="100" role="list" aria-label="Certifications" style="grid-template-columns:repeat(auto-fit,minmax(160px,1fr));">
                    @foreach ($featuredCertifications as $cert)
                        @php $ct = $cert->translate(app()->getLocale()); @endphp
                        <x-front.client-logo
                            :name="$ct?->title ?? $cert->issuer ?? '—'"
                            :image="$cert->featured_image_url"
                            icon="bi-patch-check-fill"
                            url="#"
                        />
                    @endforeach
                </div>
            </div>
        </section>

        {{-- <div class="section-divider"></div> --}}
    @endif

    {{-- ============================================================
         SECTION 7 — FAQ
         ============================================================ --}}
    @if ($homeFaqs->isNotEmpty())
    <section id="faq" class="faq-section section-pad">
        <div class="container">
            <div class="faq-header" data-aos="fade-up">
                <x-front.section-heading
                    :eyebrow="__('front.home.faq.eyebrow')"
                    :title="__('front.home.faq.title')"
                    :highlight="__('front.home.faq.highlight')"
                    :description="__('front.home.faq.desc')"
                />
            </div>

            <div class="faq-wrap" data-aos="fade-up" data-aos-delay="100">
                @foreach ($homeFaqs as $faq)
                    <x-front.faq-item :question="$faq['question']" :answer="$faq['answer']" :open="$loop->first" />
                @endforeach
            </div>

            <div class="text-center mt-5" data-aos="fade-up">
                <p class="section-desc" style="text-align:center;margin-bottom:24px;">{{ __('front.home.faq.cta') }}</p>
                <a href="{{ route('contact', app()->getLocale()) }}" class="btn-gold">
                    <i class="bi bi-chat-dots-fill" aria-hidden="true"></i>
                    <span>{{ __('front.home.faq.ask_experts') }}</span>
                </a>
            </div>
        </div>
    </section>
    @endif

@endsection
