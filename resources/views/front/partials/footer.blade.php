@php
    $locale = app()->getLocale();
    $siteSettings = $siteSettings ?? \App\Data\WebsiteSettingsBag::make($locale);
    $companyLinks = config('front-nav.footer.company', []);

    $brand = setting('general.site_name') ?: __('common.app_name_short');
    $tagline = setting('general.site_tagline') ?: __('common.app_tagline');
    $logo = setting_url('general.logo');
    $aboutText = setting('footer.about') ?: __('footer.tagline');

    $address = setting('contact.address') ?: __('common.address_multiline');
    $phones = $siteSettings->phones();
    $emails = $siteSettings->emails();

    $copyrightTemplate = $siteSettings->copyrightText() ?: __('footer.copyright');
    $copyright = str_replace(':year', (string) date('Y'), (string) $copyrightTemplate);

    $socialLinks = $siteSettings->socialLinks();
    $featuredServices = $featuredServices ?? collect();
    $certBadges = $siteSettings->certificationBadges();
@endphp

<footer class="site-footer" role="contentinfo">
    <div class="footer-bg" aria-hidden="true">
        <span class="footer-bg-orb footer-bg-orb--1"></span>
        <span class="footer-bg-orb footer-bg-orb--2"></span>
    </div>

    <div class="footer-cta">
        <div class="container">
            <div class="footer-cta-box" data-aos="fade-up">
                <div class="footer-cta-content">
                    <span class="footer-cta-eyebrow">{{ __('footer.cta.overline') }}</span>
                    <h2 class="footer-cta-title">{{ __('footer.cta.title') }}</h2>
                    <p class="footer-cta-desc">{{ __('footer.cta.subtitle') }}</p>
                </div>
                <a href="{{ route('contact', $locale) }}" class="btn-gold footer-cta-btn">
                    <i class="bi bi-envelope-fill" aria-hidden="true"></i>
                    <span>{{ __('buttons.contact_us') }}</span>
                </a>
            </div>
        </div>
    </div>

    <div class="footer-main">
        <div class="container">
            <div class="footer-grid">

                <div class="footer-col footer-col--brand">
                    <a href="{{ route('home', $locale) }}" class="footer-logo nav-logo" aria-label="{{ $brand }}">
                        @if ($logo)
                            <img src="{{ $logo }}" alt="" class="nav-logo-icon" width="46" height="46">
                        @else
                            <svg class="nav-logo-icon" viewBox="0 0 46 46" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <polygon points="23,2 44,12.5 44,33.5 23,44 2,33.5 2,12.5" fill="#c9a227" opacity="0.12"/>
                                <polygon points="23,2 44,12.5 44,33.5 23,44 2,33.5 2,12.5" fill="none" stroke="#c9a227" stroke-width="1.5"/>
                                <text x="50%" y="57%" dominant-baseline="middle" text-anchor="middle" font-family="Poppins,Arial" font-weight="900" font-size="14" fill="#c9a227">IK</text>
                            </svg>
                        @endif
                        <div class="nav-logo-text">
                            <span class="nav-logo-main">IK <span>{{ Str::limit(Str::after($brand, 'IK '), 12, '') ?: 'Saudi' }}</span></span>
                            <span class="nav-logo-sub">{{ $tagline }}</span>
                        </div>
                    </a>

                    <p class="footer-about-text">{{ $aboutText }}</p>

                    @if (count($certBadges))
                        <div class="footer-cert-badges">
                            @foreach ($certBadges as $badge)
                                <span class="footer-cert-badge">
                                    {{ is_array($badge) ? ($badge['label'] ?? '') : $badge }}
                                </span>
                            @endforeach
                        </div>
                    @endif

                    @if (count($socialLinks))
                        <div class="footer-socials" aria-label="{{ __('footer.socials') }}">
                            @foreach ($socialLinks as $link)
                                <a href="{{ $link['url'] ?? '#' }}"
                                   class="footer-social-btn"
                                   aria-label="{{ $link['label'] ?? ($link['platform'] ?? '') }}"
                                   target="_blank" rel="noopener noreferrer">
                                    <i class="bi {{ 'bi-' . strtolower($link['platform'] ?? 'globe2') }}"></i>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="footer-col footer-col--links">
                    <div class="footer-links-group">
                        <h3 class="footer-heading">{{ __('common.company') }}</h3>
                        <ul class="footer-links">
                            @foreach ($companyLinks as $link)
                                @if (Route::has($link['route']))
                                    <li>
                                        <a href="{{ in_array($link['route'], ['products.index', 'products.show'], true) ? route($link['route']) : route($link['route'], $locale) }}">
                                            <i class="bi bi-chevron-right footer-link-icon" aria-hidden="true"></i>
                                            <span>{{ __($link['label']) }}</span>
                                        </a>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </div>

                    <div class="footer-links-group">
                        <h3 class="footer-heading">{{ __('footer.our_services') }}</h3>
                        <ul class="footer-links">
                            @forelse ($featuredServices->take(6) as $service)
                                @php $st = $service->translate($locale); @endphp
                                @if ($st)
                                    <li>
                                        <a href="{{ route('services.show', [$locale, $st->slug]) }}">
                                            <i class="bi bi-chevron-right footer-link-icon" aria-hidden="true"></i>
                                            <span>{{ $st->title }}</span>
                                        </a>
                                    </li>
                                @endif
                            @empty
                                <li>
                                    <a href="{{ route('services.index', $locale) }}">
                                        <i class="bi bi-chevron-right footer-link-icon" aria-hidden="true"></i>
                                        <span>{{ __('navigation.all_services') }}</span>
                                    </a>
                                </li>
                            @endforelse
                        </ul>
                    </div>
                </div>

                <div class="footer-col footer-col--contact">
                    <h3 class="footer-heading">{{ __('footer.get_in_touch') }}</h3>

                    <div class="footer-contact-cards">
                        @if ($address)
                            <div class="footer-contact-card">
                                <div class="footer-contact-icon"><i class="bi bi-geo-alt-fill" aria-hidden="true"></i></div>
                                <div class="footer-contact-text">
                                    <strong>{{ __('footer.headquarters') }}</strong>
                                    <span>{!! nl2br(e($address)) !!}</span>
                                </div>
                            </div>
                        @endif

                        @foreach ($phones as $phone)
                            <div class="footer-contact-card">
                                <div class="footer-contact-icon"><i class="bi bi-telephone-fill" aria-hidden="true"></i></div>
                                <div class="footer-contact-text">
                                    <strong>{{ $phone['label'] ?? __('footer.phone') }}</strong>
                                    <a href="tel:{{ preg_replace('/\s+/', '', $phone['number'] ?? '') }}">{{ $phone['number'] }}</a>
                                </div>
                            </div>
                            @break($loop->iteration >= 2)
                        @endforeach

                        @foreach ($emails as $email)
                            <div class="footer-contact-card">
                                <div class="footer-contact-icon"><i class="bi bi-envelope-fill" aria-hidden="true"></i></div>
                                <div class="footer-contact-text">
                                    <strong>{{ $email['label'] ?? __('footer.email') }}</strong>
                                    <a href="mailto:{{ $email['address'] ?? '' }}">{{ $email['address'] }}</a>
                                </div>
                            </div>
                            @break($loop->iteration >= 1)
                        @endforeach
                    </div>

                    <div class="footer-newsletter-card">
                        <p class="footer-newsletter-title">
                            <i class="bi bi-newspaper" aria-hidden="true"></i>
                            {{ __('footer.newsletter_blurb') }}
                        </p>
                        <form class="newsletter-form" novalidate>
                            <label class="visually-hidden" for="footer-newsletter-email">{{ __('footer.newsletter_placeholder') }}</label>
                            <input type="email"
                                   id="footer-newsletter-email"
                                   class="newsletter-input"
                                   placeholder="{{ __('footer.newsletter_placeholder') }}"
                                   required>
                            <button type="submit" class="newsletter-btn" aria-label="{{ __('footer.subscribe') }}">
                                <i class="bi bi-send-fill" aria-hidden="true"></i>
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="footer-bottom">
        <div class="container">
            <div class="footer-bottom-inner">
                <p class="footer-copy">© {{ $copyright }}</p>
                <ul class="footer-bottom-links">
                    <li><a href="#">{{ __('common.privacy') }}</a></li>
                    <li><a href="#">{{ __('common.terms') }}</a></li>
                </ul>
            </div>
        </div>
    </div>
</footer>
