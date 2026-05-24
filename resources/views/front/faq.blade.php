@extends('front.layouts.app')

@section('title', __('navigation.faq'))
@section('meta_description', __('front.faq.subtitle'))

@section('content')

    <x-front.page-hero
        :tag="__('front.faq.tag')"
        icon="bi-patch-question-fill"
        :title="__('front.faq.title')"
        :highlight="__('front.faq.highlight')"
        :subtitle="__('front.faq.subtitle')"
    />

    <x-front.breadcrumb :items="[['label' => __('front.faq.breadcrumb')]]" />

    {{-- FAQ Accordions --}}
    <section class="section-pad bg-dark1">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-8 mx-auto">
                    @forelse ($categories as $category)
                        @php $catKey = is_array($category) ? ($category['key'] ?? '') : ($category->key ?? ''); @endphp
                        @php $catTitle = is_array($category) ? ($category['title'] ?? '') : ($category->title ?? ''); @endphp
                        @php $catItems = is_array($category) ? ($category['items'] ?? []) : ($category->items ?? []); @endphp

                        @if (count($catItems))
                            <div class="mb-5" data-aos="fade-up">
                                @if ($catTitle)
                                    <h3 class="section-title" style="font-size:1.4rem;margin-bottom:24px;">
                                        <span class="accent">{{ $catTitle }}</span>
                                    </h3>
                                @endif
                                <div class="faq-accordion" id="faq-acc-{{ $catKey }}">
                                    @foreach ($catItems as $idx => $item)
                                        @php $q = is_array($item) ? ($item['question'] ?? '') : ($item->question ?? ''); @endphp
                                        @php $a = is_array($item) ? ($item['answer'] ?? '') : ($item->answer ?? ''); @endphp
                                        <x-front.faq-item
                                            :question="$q"
                                            :answer="$a"
                                            :open="$loop->first"
                                        />
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @empty
                        <p class="text-center" style="color:var(--c-muted);padding:40px 0;">{{ __('front.faq.no_faqs') }}</p>
                    @endforelse
                </div>
            </div>
        </div>
    </section>

    <x-front.cta-section :title="__('front.faq.cta_title')" :description="__('front.faq.cta_desc')">
        <a href="{{ route('contact', app()->getLocale()) }}" class="btn-gold">
            <i class="bi bi-chat-dots-fill" aria-hidden="true"></i>
            <span>{{ __('front.faq.ask_question') }}</span>
        </a>
        @php $wa = $siteSettings?->whatsappFormatted('966591154300'); @endphp
        @if ($wa)
            <a href="{{ $wa }}" class="btn-outline-gold" target="_blank" rel="noopener">
                <i class="bi bi-whatsapp" aria-hidden="true"></i>
                <span>{{ __('buttons.chat_whatsapp') }}</span>
            </a>
        @endif
    </x-front.cta-section>

@endsection
