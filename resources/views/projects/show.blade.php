@extends('front.layouts.app')

@php
    $locale = app()->getLocale();
    $pt = $translation;
    $title = $pt?->title ?? '—';
    $summary = $pt?->summary ?? '';
@endphp

@section('title', $title)
@section('meta_description', $seo?->meta_description ?: ($summary ?: __('projects.index.subtitle')))

@push('seo')
    @if ($seo?->meta_keywords)
        <meta name="keywords" content="{{ $seo->meta_keywords }}">
    @endif
@endpush

@section('content')

    <x-front.page-hero
        :tag="__('navigation.projects')"
        icon="bi-kanban-fill"
        :title="$title"
        :subtitle="$summary"
    />

    <x-front.breadcrumb :items="[
        ['label' => __('navigation.projects'), 'url' => route('projects.index', $locale)],
        ['label' => $title],
    ]" />

    <section class="section-pad bg-dark1">
        <div class="container">
            <div class="row g-5">

                <div class="col-lg-8" data-aos="fade-right">
                    @if ($project->featured_image_url)
                        <div style="height:420px;border-radius:var(--radius-xl);overflow:hidden;margin-bottom:40px;background-image:url('{{ $project->featured_image_url }}');background-size:cover;background-position:center;"></div>
                    @else
                        <div style="height:420px;border-radius:var(--radius-xl);overflow:hidden;margin-bottom:40px;background:linear-gradient(135deg,#0d2137 0%,#1a3a5c 40%,#0a1020 100%);display:flex;align-items:center;justify-content:center;">
                            <i class="bi bi-kanban-fill" style="font-size:10rem;color:rgba(201,162,39,0.15);" aria-hidden="true"></i>
                        </div>
                    @endif

                    @if ($summary)
                        <p class="section-desc mb-4">{{ $summary }}</p>
                    @endif

                    @if ($pt?->body)
                        <div class="prose-light" style="color:var(--c-muted);line-height:1.8;">{!! safe_html($pt->body) !!}</div>
                    @endif

                    @if (is_array($pt?->highlights) && count($pt->highlights) > 0)
                        <h3 style="font-size:1.2rem;font-weight:700;color:var(--c-white);margin:36px 0 20px;">{{ __('common.highlights') }}</h3>
                        <ul style="list-style:none;padding:0;">
                            @foreach ($pt->highlights as $item)
                                <li style="display:flex;gap:12px;align-items:flex-start;padding:10px 0;color:var(--c-muted);">
                                    <i class="bi bi-check-circle-fill" style="color:var(--c-gold);margin-top:4px;flex-shrink:0;" aria-hidden="true"></i>
                                    <span>{{ is_array($item) ? ($item['text'] ?? '') : $item }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>

                <div class="col-lg-4" data-aos="fade-left">
                    <div style="background:rgba(255,255,255,0.025);border:1px solid rgba(255,255,255,0.06);border-radius:var(--radius-xl);padding:28px;margin-bottom:24px;">
                        <h4 style="font-size:0.75rem;font-weight:700;letter-spacing:3px;text-transform:uppercase;color:var(--c-gold);margin-bottom:20px;">{{ __('projects.show.project_details') }}</h4>
                        <dl style="margin:0;">
                            @if ($project->client_name)
                                <div style="margin-bottom:14px;">
                                    <dt style="font-size:0.74rem;color:var(--c-muted);">{{ __('projects.show.client') }}</dt>
                                    <dd style="font-size:0.92rem;color:var(--c-white);margin:4px 0 0;">{{ $project->client_name }}</dd>
                                </div>
                            @endif
                            @if ($project->location)
                                <div style="margin-bottom:14px;">
                                    <dt style="font-size:0.74rem;color:var(--c-muted);">{{ __('projects.show.location') }}</dt>
                                    <dd style="font-size:0.92rem;color:var(--c-white);margin:4px 0 0;">{{ $project->location }}</dd>
                                </div>
                            @endif
                            @if ($project->year)
                                <div style="margin-bottom:14px;">
                                    <dt style="font-size:0.74rem;color:var(--c-muted);">{{ __('projects.show.year') }}</dt>
                                    <dd style="font-size:0.92rem;color:var(--c-white);margin:4px 0 0;">{{ $project->year }}</dd>
                                </div>
                            @endif
                        </dl>

                        @if ($project->services->isNotEmpty())
                            <div style="border-top:1px solid rgba(255,255,255,0.06);padding-top:18px;margin-top:18px;">
                                <p style="font-size:0.74rem;font-weight:700;letter-spacing:2px;text-transform:uppercase;color:var(--c-gold);margin-bottom:12px;">{{ __('navigation.services') }}</p>
                                <ul style="list-style:none;padding:0;">
                                    @foreach ($project->services as $service)
                                        @php $st = $service->translate($locale); @endphp
                                        @if ($st)
                                            <li style="border-bottom:1px solid rgba(255,255,255,0.05);padding:8px 0;">
                                                <a href="{{ route('services.show', [$locale, $st->slug]) }}" style="font-size:0.86rem;color:var(--c-muted);text-decoration:none;">
                                                    <i class="bi {{ $service->icon ?: 'bi-gear-fill' }}" style="color:var(--c-gold);margin-inline-end:8px;" aria-hidden="true"></i>{{ $st->title }}
                                                </a>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <a href="{{ route('contact', $locale) }}" class="btn-gold" style="width:100%;justify-content:center;margin-top:18px;">
                            <i class="bi bi-envelope-fill" aria-hidden="true"></i>
                            <span>{{ __('buttons.discuss_similar') ?? __('buttons.request_quote') }}</span>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </section>

@endsection
