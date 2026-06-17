@php
    $brand = setting('general.site_name') ?: __('common.app_name_short');
    $loaderLogo = setting_url('branding.loading_logo') ?? setting_url('general.logo');
@endphp
<div id="loading-screen" role="status" aria-label="{{ __('common.loading') }}">
    <div class="loader-logo">
        @if ($loaderLogo)
            <img src="{{ $loaderLogo }}" alt="{{ $brand }}" class="loader-logo-img" width="200" height="140">
        @else
            <svg class="loader-hex" viewBox="0 0 54 54" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <polygon points="27,3 51,15 51,39 27,51 3,39 3,15" fill="#c9a227" opacity="0.15"/>
                <polygon points="27,3 51,15 51,39 27,51 3,39 3,15" fill="none" stroke="#c9a227" stroke-width="1.5"/>
                <text x="50%" y="56%" dominant-baseline="middle" text-anchor="middle" font-family="Poppins,Arial" font-weight="900" font-size="16" fill="#c9a227">IK</text>
            </svg>
        @endif
        <div class="loader-brand">IK <span>SAUDI</span></div>
    </div>
    <div class="loader-circles" aria-hidden="true">
        <span></span>
        <span></span>
        <span></span>
    </div>
</div>
