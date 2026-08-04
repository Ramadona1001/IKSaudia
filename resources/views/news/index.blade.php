@extends('front.layouts.app')

@php
    $locale = app()->getLocale();
@endphp

@section('title', __('navigation.news'))
@section('meta_description', __('news.index.subtitle'))

@section('content')
    <x-front.page-hero
        :tag="__('navigation.news')"
        icon="bi-newspaper"
        :title="__('navigation.news')"
        :subtitle="__('news.index.subtitle')"
    />

    <x-front.breadcrumb :items="[
        ['label' => __('navigation.news')],
    ]" />

    <section class="section-pad">
        <div class="container">
            @if ($posts->isEmpty())
                <p class="text-center text-muted">{{ __('common.no_results') }}</p>
            @else
                <div class="row g-4">
                    @foreach ($posts as $post)
                        @php $pt = $post->translate($locale); @endphp
                        @if ($pt)
                            <div class="col-md-6 col-lg-4" data-aos="fade-up">
                                <article class="service-card h-100">
                                    @if ($post->featured_image_url)
                                        <a href="{{ route('news.show', [$locale, $pt->slug]) }}" class="service-card-media" style="background-image:url('{{ $post->featured_image_url }}');"></a>
                                    @endif
                                    <div class="service-card-body">
                                        <h3 class="service-card-title">
                                            <a href="{{ route('news.show', [$locale, $pt->slug]) }}">{{ $pt->title }}</a>
                                        </h3>
                                        @if ($pt->excerpt)
                                            <p class="service-card-text">{{ Str::limit($pt->excerpt, 140) }}</p>
                                        @endif
                                        <a href="{{ route('news.show', [$locale, $pt->slug]) }}" class="service-card-link">
                                            {{ __('common.read_more') }} →
                                        </a>
                                    </div>
                                </article>
                            </div>
                        @endif
                    @endforeach
                </div>

                @if ($posts->hasPages())
                    <div class="mt-5 d-flex justify-content-center">
                        {{ $posts->links() }}
                    </div>
                @endif
            @endif
        </div>
    </section>
@endsection
