@php
    $locale = app()->getLocale();
    $searchI18n = [
        'loading' => __('common.searching'),
        'noResults' => __('common.no_results'),
        'types' => __('common.search_types'),
    ];
@endphp

<div
    class="search-modal"
    role="dialog"
    aria-modal="true"
    aria-label="{{ __('common.search') }}"
    data-search-url="{{ route('search', $locale) }}"
    data-min-length="2"
    data-i18n="{{ json_encode($searchI18n, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT) }}"
>
    <div class="search-modal-inner">
        <div class="search-input-wrap">
            <i class="bi bi-search" aria-hidden="true"></i>
            <input
                type="search"
                class="search-input"
                placeholder="{{ __('common.search_placeholder') }}"
                aria-label="{{ __('common.search') }}"
                aria-controls="search-results"
                aria-expanded="false"
                autocomplete="off"
            >
            <button class="search-close" type="button" aria-label="{{ __('common.close') }}">
                <i class="bi bi-x-lg" aria-hidden="true"></i>
            </button>
        </div>

        <div class="search-status" id="search-status" hidden></div>

        <ul class="search-results" id="search-results" role="listbox" hidden></ul>

        <p class="search-hint">{{ __('common.search_hint') }}</p>
    </div>
</div>
