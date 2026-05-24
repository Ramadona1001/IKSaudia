@php $locale = app()->getLocale(); @endphp
<footer class="border-t border-white/10 bg-brand-900">
    <div class="mx-auto max-w-7xl px-4 py-12 lg:px-8">
        <div class="grid gap-8 md:grid-cols-3">
            <div>
                <p class="text-lg font-semibold text-white">IK Saudi</p>
                <p class="mt-2 text-sm text-brand-steel">
                    {{ __('footer.tagline_compact') }}
                </p>
            </div>
            <div>
                <p class="text-sm font-semibold uppercase tracking-wider text-brand-accent">{{ __('navigation.contact') }}</p>
                <ul class="mt-3 space-y-2 text-sm text-brand-steel">
                    <li>+966 13 809 5254</li>
                    <li>info@iksaudi.com</li>
                    <li>{{ __('footer.location_city') }}</li>
                </ul>
            </div>
            <div>
                <p class="text-sm font-semibold uppercase tracking-wider text-brand-accent">{{ __('footer.address') }}</p>
                <p class="mt-3 text-sm leading-relaxed text-brand-steel">
                    {{ __('footer.address_short') }}
                </p>
            </div>
        </div>
        <p class="mt-10 border-t border-white/10 pt-6 text-center text-xs text-brand-steel">
            &copy; {{ __('footer.copyright', ['year' => date('Y')]) }} {{ __('common.all_rights_reserved') }}
        </p>
    </div>
</footer>
