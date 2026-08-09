@php
    $locale = app()->getLocale();

    if (! setting('general.cookie_consent_enabled', true)) {
        return;
    }

    $message = setting('general.cookie_consent_message')
        ?: __('front.cookies.message', [], $locale);
    $acceptLabel = setting('general.cookie_consent_accept_label')
        ?: __('front.cookies.accept', [], $locale);
    $policyUrl = setting('general.cookie_consent_policy_url')
        ?: \App\Support\LegalLink::url('privacy-policy', $locale);
@endphp

<div
    id="cookie-consent"
    class="cookie-consent"
    role="dialog"
    aria-live="polite"
    aria-label="{{ __('front.cookies.title', [], $locale) }}"
    hidden
>
    <div class="cookie-consent__inner">
        <p class="cookie-consent__message">
            {{ $message }}
            @if ($policyUrl && $policyUrl !== '#')
                <a href="{{ $policyUrl }}" class="cookie-consent__link">{{ __('front.cookies.learn_more', [], $locale) }}</a>
            @endif
        </p>
        <button type="button" class="btn-gold cookie-consent__accept" data-cookie-accept>
            {{ $acceptLabel }}
        </button>
    </div>
</div>
