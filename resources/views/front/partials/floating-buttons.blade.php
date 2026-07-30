@php
    $siteSettings = $siteSettings ?? \App\Data\WebsiteSettingsBag::make(app()->getLocale());
    $whatsapp = $siteSettings->whatsappFormatted();
@endphp

@if ($whatsapp)
    <a href="{{ $whatsapp }}"
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
