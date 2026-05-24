<div class="search-modal" role="dialog" aria-modal="true" aria-label="{{ __('common.search') }}">
    <div class="search-modal-inner">
        <div class="search-input-wrap">
            <i class="bi bi-search" aria-hidden="true"></i>
            <input type="search" class="search-input" placeholder="{{ __('common.search_placeholder') }}" aria-label="{{ __('common.search') }}">
            <button class="search-close" type="button" aria-label="{{ __('common.close') }}">
                <i class="bi bi-x-lg" aria-hidden="true"></i>
            </button>
        </div>
        <p style="font-size:0.82rem;color:var(--c-muted);margin-top:14px;">
            {{ __('common.search_hint') }}
        </p>
    </div>
</div>
