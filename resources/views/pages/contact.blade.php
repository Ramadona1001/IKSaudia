@extends('layouts.app')

@php $locale = app()->getLocale(); @endphp

@section('title', __('contact.meta_title') . ' — ' . config('app.name'))

@section('content')
    <x-ui.page-hero
        :overline="__('contact.overline')"
        :title="__('contact.title')"
        :subtitle="__('contact.subtitle')"
    />

    <section class="section-padding">
        <div class="container-iks">
            @if (session('contact_success'))
                <div class="mb-10 rounded-xl border border-accent/30 bg-accent/10 px-6 py-4 text-accent reveal" role="status">
                    {{ __('contact.success_reference', ['reference' => session('contact_success')]) }}
                </div>
            @endif

            <div class="grid gap-12 lg:grid-cols-2 reveal">
                <div class="space-y-8">
                    <div class="glass-panel rounded-2xl p-8">
                        <h2 class="text-overline text-accent mb-6">{{ __('contact.info_heading') }}</h2>
                        <dl class="space-y-6">
                            <div>
                                <dt class="text-sm font-semibold text-steel-400">{{ __('footer.phone') }}</dt>
                                <dd class="mt-2 space-y-1">
                                    @foreach ($siteSettings->phones() as $phone)
                                        <a href="tel:{{ preg_replace('/\s+/', '', $phone['number'] ?? '') }}" class="block {{ !empty($phone['is_primary']) ? 'text-white' : 'text-steel-300' }} hover:text-accent transition">{{ $phone['number'] }}</a>
                                    @endforeach
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-semibold text-steel-400">{{ __('footer.email') }}</dt>
                                <dd class="mt-2 space-y-1">
                                    @foreach ($siteSettings->emails() as $email)
                                        <a href="mailto:{{ $email['address'] }}" class="block text-white hover:text-accent transition">{{ $email['address'] }}</a>
                                    @endforeach
                                </dd>
                            </div>
                            @if (setting('contact.whatsapp'))
                                <div>
                                    <dt class="text-sm font-semibold text-steel-400">WhatsApp</dt>
                                    <dd class="mt-2">
                                        <a href="https://wa.me/{{ preg_replace('/\D+/', '', setting('contact.whatsapp')) }}" class="text-accent hover:underline" target="_blank" rel="noopener">{{ setting('contact.whatsapp') }}</a>
                                    </dd>
                                </div>
                            @endif
                            <div>
                                <dt class="text-sm font-semibold text-steel-400">{{ __('footer.address') }}</dt>
                                <dd class="mt-2 leading-relaxed text-steel-300 whitespace-pre-line">
                                    {{ setting('contact.address') ?: __('contact.address_inline') }}
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <div class="glass-panel rounded-2xl p-8 lg:p-10">
                    <h2 class="text-xl font-semibold text-white mb-6">{{ __('contact.form_heading') }}</h2>

                    <form method="POST" action="{{ route('contact.store', $locale) }}" class="space-y-5" novalidate>
                        @csrf
                        <input type="hidden" name="form_started_at" value="{{ $formStartedAt ?? time() }}">
                        {{-- Honeypot: leave empty --}}
                        <div class="absolute -left-[9999px] top-auto h-0 w-0 overflow-hidden" aria-hidden="true" tabindex="-1">
                            <label for="website">{{ __('contact.honeypot_label') }}</label>
                            <input type="text" name="website" id="website" value="" autocomplete="off" tabindex="-1">
                        </div>

                        <x-ui.form-field :label="__('contact.fields.name')" name="name" required />
                        <x-ui.form-field :label="__('contact.fields.email')" name="email" type="email" required />
                        <x-ui.form-field :label="__('contact.fields.phone')" name="phone" type="tel" />
                        <x-ui.form-field :label="__('contact.fields.company')" name="company" />
                        <x-ui.form-field :label="__('contact.fields.subject')" name="subject" />
                        <x-ui.form-field :label="__('contact.fields.message')" name="message" type="textarea" required />

                        <x-ui.button type="submit" class="w-full justify-center sm:w-auto">
                            {{ __('buttons.send_message') }}
                        </x-ui.button>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection
