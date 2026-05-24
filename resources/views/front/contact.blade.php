@extends('front.layouts.app')

@section('title', __('navigation.contact'))
@section('meta_description', __('front.contact.subtitle'))

@section('content')

    <x-front.page-hero
        :tag="__('front.contact.tag')"
        icon="bi-envelope-fill"
        :title="__('front.contact.title')"
        :highlight="__('front.contact.highlight')"
        :subtitle="__('front.contact.subtitle')"
    />

    <x-front.breadcrumb :items="[['label' => __('front.contact.breadcrumb')]]" />

    @php
        $phones = $siteSettings?->phones() ?? [];
        $emails = $siteSettings?->emails() ?? [];
        $address = $siteSettings?->localizedAddress(app()->getLocale())
            ?: setting('contact.address')
            ?: __('footer.address_short');
        $whatsapp = $siteSettings?->whatsappFormatted('966591154300');
        $businessHours = $siteSettings?->businessHours(app()->getLocale()) ?? [];
        $mapQuery = urlencode(strip_tags(str_replace(["\r", "\n"], ' ', $address)));
        $mapsUrl = $mapQuery !== '' ? 'https://www.google.com/maps/search/?api=1&query='.$mapQuery : null;
    @endphp

    <section class="section-pad bg-dark1 contact-page">
        <div class="container">

            {{-- Quick contact cards --}}
            <div class="row g-4 contact-info-row">
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="0">
                    <div class="contact-card h-100">
                        <div class="contact-card-icon gold"><i class="bi bi-geo-alt-fill" aria-hidden="true"></i></div>
                        <h3 class="contact-card-title">{{ __('front.contact.cards.hq_title') }}</h3>
                        <p class="contact-card-info">{!! nl2br(e($address)) !!}</p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="80">
                    <div class="contact-card h-100">
                        <div class="contact-card-icon blue"><i class="bi bi-telephone-fill" aria-hidden="true"></i></div>
                        <h3 class="contact-card-title">{{ __('front.contact.cards.phone_title') }}</h3>
                        <p class="contact-card-info">
                            @forelse ($phones as $phone)
                                @if ($loop->index > 0)<br>@endif
                                @if (! empty($phone['label']))
                                    <span class="contact-card-meta">{{ $phone['label'] }}:</span>
                                @endif
                                <a href="tel:{{ preg_replace('/\s+/', '', $phone['number'] ?? '') }}">{{ $phone['number'] }}</a>
                            @empty
                                —
                            @endforelse
                        </p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="160">
                    <div class="contact-card h-100">
                        <div class="contact-card-icon gold"><i class="bi bi-envelope-fill" aria-hidden="true"></i></div>
                        <h3 class="contact-card-title">{{ __('front.contact.cards.email_title') }}</h3>
                        <p class="contact-card-info">
                            @forelse ($emails as $email)
                                @if ($loop->index > 0)<br>@endif
                                <a href="mailto:{{ $email['address'] ?? '' }}">{{ $email['address'] }}</a>
                            @empty
                                —
                            @endforelse
                        </p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="240">
                    <div class="contact-card h-100">
                        <div class="contact-card-icon blue"><i class="bi bi-clock-fill" aria-hidden="true"></i></div>
                        <h3 class="contact-card-title">{{ __('front.contact.cards.hours_title') }}</h3>
                        <p class="contact-card-info">
                            @foreach ($businessHours as $line)
                                @if (! $loop->first)<br>@endif
                                {{ $line }}
                            @endforeach
                        </p>
                    </div>
                </div>
            </div>

            {{-- Form + sidebar --}}
            <div class="row g-5 contact-layout-row align-items-start">
                <div class="col-lg-7" data-aos="fade-up">
                    <div class="contact-form-wrap">
                        <div class="section-eyebrow">{{ __('front.contact.form_eyebrow') }}</div>
                        <h2 class="contact-form-title">
                            <span>{{ __('front.contact.form_title1') }}</span>
                            <span class="accent">{{ __('front.contact.form_title2') }}</span>
                        </h2>
                        <p class="contact-form-intro">{{ __('front.contact.form_intro') }}</p>

                        @if (session('contact_success'))
                            <div class="contact-alert contact-alert--success" role="status" aria-live="polite">
                                <i class="bi bi-check-circle-fill" aria-hidden="true"></i>
                                <span>{{ session('contact_success') }}</span>
                            </div>
                        @endif

                        @if ($errors->any())
                            <div class="contact-alert contact-alert--error" role="alert">
                                <i class="bi bi-exclamation-triangle-fill" aria-hidden="true"></i>
                                <span>{{ __('front.contact.error_summary') }}</span>
                            </div>
                        @endif

                        <form class="contact-form" method="POST" action="{{ route('contact.store', app()->getLocale()) }}" data-real-submit novalidate>
                            @csrf
                            <input type="hidden" name="form_started_at" value="{{ $formStartedAt ?? time() }}">

                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label" for="contact-name">{{ __('front.contact.fields.name') }} <span class="text-gold">*</span></label>
                                        <input id="contact-name" name="name" type="text" class="form-control-custom @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="{{ __('front.contact.fields.name_ph') }}" required>
                                        @error('name')<small class="form-error">{{ $message }}</small>@enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label" for="contact-company">{{ __('front.contact.fields.company') }}</label>
                                        <input id="contact-company" name="company" type="text" class="form-control-custom @error('company') is-invalid @enderror" value="{{ old('company') }}" placeholder="{{ __('front.contact.fields.company_ph') }}">
                                        @error('company')<small class="form-error">{{ $message }}</small>@enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label" for="contact-email">{{ __('front.contact.fields.email') }} <span class="text-gold">*</span></label>
                                        <input id="contact-email" name="email" type="email" class="form-control-custom @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="{{ __('front.contact.fields.email_ph') }}" required>
                                        @error('email')<small class="form-error">{{ $message }}</small>@enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label" for="contact-phone">{{ __('front.contact.fields.phone') }}</label>
                                        <input id="contact-phone" name="phone" type="tel" class="form-control-custom @error('phone') is-invalid @enderror" value="{{ old('phone') }}" placeholder="{{ __('front.contact.fields.phone_ph') }}">
                                        @error('phone')<small class="form-error">{{ $message }}</small>@enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="form-label" for="contact-subject">{{ __('front.contact.fields.subject') }} <span class="text-gold">*</span></label>
                                        <input id="contact-subject" name="subject" type="text" class="form-control-custom @error('subject') is-invalid @enderror" value="{{ old('subject') }}" placeholder="{{ __('front.contact.fields.subject_ph') }}" required>
                                        @error('subject')<small class="form-error">{{ $message }}</small>@enderror
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group mb-0">
                                        <label class="form-label" for="contact-message">{{ __('front.contact.fields.message') }} <span class="text-gold">*</span></label>
                                        <textarea id="contact-message" name="message" rows="5" class="form-control-custom @error('message') is-invalid @enderror" placeholder="{{ __('front.contact.fields.message_ph') }}" required>{{ old('message') }}</textarea>
                                        @error('message')<small class="form-error">{{ $message }}</small>@enderror
                                    </div>
                                </div>
                            </div>

                            <input type="text" name="website" value="" tabindex="-1" autocomplete="off" class="visually-hidden" aria-hidden="true">

                            @if (config('security.turnstile.enabled') && config('security.turnstile.site_key'))
                                <div class="mb-3">
                                    <div class="cf-turnstile" data-sitekey="{{ config('security.turnstile.site_key') }}"></div>
                                    @error('cf-turnstile-response')<small class="form-error d-block">{{ $message }}</small>@enderror
                                </div>
                            @endif

                            <div class="form-check-custom">
                                <input class="form-check-input-custom @error('terms') is-invalid @enderror" type="checkbox" name="terms" id="contact-terms" value="1" {{ old('terms') ? 'checked' : '' }} required>
                                <label class="form-check-label-custom" for="contact-terms">
                                    {!! __('front.contact.terms_html') !!}
                                </label>
                            </div>
                            @error('terms')<small class="form-error d-block mt-2">{{ $message }}</small>@enderror

                            <button type="submit" class="btn-gold contact-submit-btn">
                                <i class="bi bi-send-fill" aria-hidden="true"></i>
                                <span>{{ __('front.contact.submit') }}</span>
                            </button>
                        </form>
                    </div>
                </div>

                <div class="col-lg-5 contact-sidebar" data-aos="fade-up" data-aos-delay="100">
                    @if ($mapsUrl)
                        <a href="{{ $mapsUrl }}" class="map-section contact-map-link" target="_blank" rel="noopener noreferrer">
                            <i class="bi bi-geo-alt-fill" aria-hidden="true"></i>
                            <p>{{ __('front.contact.map_title') }}</p>
                            <small>{{ $address }}</small>
                            <span class="contact-map-cta">
                                <i class="bi bi-box-arrow-up-right" aria-hidden="true"></i>
                                {{ __('buttons.view_on_map') }}
                            </span>
                        </a>
                    @else
                        <div class="map-section">
                            <i class="bi bi-geo-alt-fill" aria-hidden="true"></i>
                            <p>{{ __('front.contact.map_title') }}</p>
                            <small>{{ $address }}</small>
                        </div>
                    @endif

                    @if ($whatsapp)
                        <a href="{{ $whatsapp }}" class="contact-whatsapp-card" target="_blank" rel="noopener noreferrer">
                            <div class="contact-whatsapp-icon"><i class="bi bi-whatsapp" aria-hidden="true"></i></div>
                            <div>
                                <div class="contact-whatsapp-title">{{ __('front.contact.whatsapp_title') }}</div>
                                <div class="contact-whatsapp-desc">{{ __('front.contact.whatsapp_desc') }}</div>
                            </div>
                            <i class="bi bi-arrow-right contact-whatsapp-arrow" aria-hidden="true"></i>
                        </a>
                    @endif
                </div>
            </div>

        </div>
    </section>

@endsection

@push('scripts')
    @if (config('security.turnstile.enabled') && config('security.turnstile.site_key'))
        <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
    @endif
@endpush
