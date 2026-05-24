@php
    $siteSettings = $siteSettings ?? \App\Data\WebsiteSettingsBag::make(app()->getLocale());
    $whatsapp = setting('contact.whatsapp') ?: ($siteSettings->primaryPhone()['number'] ?? null);
    $whatsappNumber = $whatsapp ? preg_replace('/[^0-9]/', '', (string) $whatsapp) : null;
@endphp

@if ($whatsappNumber)
    <a href="https://wa.me/{{ $whatsappNumber }}"
       id="whatsapp-btn"
       aria-label="{{ __('common.contact_whatsapp') }}"
       target="_blank"
       rel="noopener noreferrer">
        <i class="bi bi-whatsapp" aria-hidden="true"></i>
    </a>
@endif

<button id="scroll-top" type="button" aria-label="{{ __('common.scroll_top') }}">
    <i class="bi bi-arrow-up" aria-hidden="true"></i>
</button>
