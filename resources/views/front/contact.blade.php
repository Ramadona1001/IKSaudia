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

    <x-front.breadcrumb :items="[['section' => 'contact']]" />

    @php
        $phones = $siteSettings?->phones() ?? [];
        $emails = $siteSettings?->emails() ?? [];
        $address = $siteSettings?->localizedAddress(app()->getLocale())
            ?: setting('contact.address')
            ?: __('footer.address_short');
        $whatsapp = $siteSettings?->whatsappFormatted();
        $socialLinks = $siteSettings?->socialLinks() ?? [];
        $businessHours = $siteSettings?->businessHours(app()->getLocale()) ?? [];
        $mapQuery = urlencode(strip_tags(str_replace(["\r", "\n"], ' ', $address)));
        $mapsUrl = $mapQuery !== '' ? 'https://www.google.com/maps/search/?api=1&query='.$mapQuery : null;
    @endphp

    <section class="section-pad contact-page">
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
                    @php
                        $formCopy = \App\Support\ContactForm::copy(app()->getLocale());
                        $formFields = \App\Support\ContactForm::fields();
                    @endphp
                    <div class="contact-form-wrap">
                        <div class="section-eyebrow">{{ $formCopy['eyebrow'] }}</div>
                        <h2 class="contact-form-title">
                            <span>{{ $formCopy['title'] }}</span>
                            <span class="accent">{{ $formCopy['title_accent'] }}</span>
                        </h2>
                        <p class="contact-form-intro">{{ $formCopy['intro'] }}</p>

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
                                @foreach ($formFields as $field)
                                    @php
                                        $fieldKey = $field['key'];
                                        $fieldId = 'contact-'.$fieldKey;
                                        $fieldType = $field['type'] ?? 'text';
                                        $colClass = ($field['width'] ?? 'half') === 'full' ? 'col-12' : 'col-md-6';
                                        $isRequired = (bool) ($field['is_required'] ?? false);
                                        $label = \App\Support\ContactForm::label($field);
                                        $placeholder = \App\Support\ContactForm::placeholder($field);
                                    @endphp
                                    <div class="{{ $colClass }}">
                                        <div class="form-group {{ $loop->last ? 'mb-0' : '' }}">
                                            <label class="form-label" for="{{ $fieldId }}">
                                                {{ $label }}
                                                @if ($isRequired)
                                                    <span class="text-gold">*</span>
                                                @endif
                                            </label>

                                            @if ($fieldType === 'textarea')
                                                <textarea
                                                    id="{{ $fieldId }}"
                                                    name="{{ $fieldKey }}"
                                                    rows="5"
                                                    class="form-control-custom @error($fieldKey) is-invalid @enderror"
                                                    placeholder="{{ $placeholder }}"
                                                    @if ($isRequired) required @endif
                                                >{{ old($fieldKey) }}</textarea>
                                            @elseif ($fieldType === 'select')
                                                <select
                                                    id="{{ $fieldId }}"
                                                    name="{{ $fieldKey }}"
                                                    class="form-control-custom @error($fieldKey) is-invalid @enderror"
                                                    @if ($isRequired) required @endif
                                                >
                                                    <option value="">{{ $placeholder ?: $label }}</option>
                                                    @foreach (\App\Support\ContactForm::selectOptions($field) as $option)
                                                        <option value="{{ $option['value'] }}" @selected(old($fieldKey) === $option['value'])>
                                                            {{ $option['label'] }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            @else
                                                <input
                                                    id="{{ $fieldId }}"
                                                    name="{{ $fieldKey }}"
                                                    type="{{ \App\Support\ContactForm::inputType($field) }}"
                                                    class="form-control-custom @error($fieldKey) is-invalid @enderror"
                                                    value="{{ old($fieldKey) }}"
                                                    placeholder="{{ $placeholder }}"
                                                    @if ($isRequired) required @endif
                                                >
                                            @endif

                                            @error($fieldKey)<small class="form-error">{{ $message }}</small>@enderror
                                        </div>
                                    </div>
                                @endforeach
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
                                    {!! __('front.contact.terms_html', ['privacy_url' => \App\Support\LegalLink::url('privacy-policy', app()->getLocale())]) !!}
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

                    @if (count($socialLinks))
                        <div class="contact-social-card">
                            <div class="contact-social-header">
                                <div class="contact-social-icon"><i class="bi bi-share-fill" aria-hidden="true"></i></div>
                                <div>
                                    <div class="contact-social-title">{{ __('front.contact.social_title') }}</div>
                                    <div class="contact-social-desc">{{ __('front.contact.social_desc') }}</div>
                                </div>
                            </div>
                            <x-front.social-links
                                :links="$socialLinks"
                                class="contact-social-links"
                                button-class="contact-social-btn"
                            />
                        </div>
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
