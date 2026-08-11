{{-- Front theme JS stack. Defer to keep the critical render path light. --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" defer></script>
<script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js" defer></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js" defer></script>
<script src="https://cdn.jsdelivr.net/npm/glightbox/dist/js/glightbox.min.js" defer></script>
<script src="{{ asset_version('assets/front/js/main.js') }}" defer></script>

@stack('scripts')
