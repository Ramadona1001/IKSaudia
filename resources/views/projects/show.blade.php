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

    <section class="section-pad projects-show-section">
        <div class="container">
            <div class="row g-5">

                <div class="col-lg-8" data-aos="fade-right">
                    @if ($project->featured_image_url)
                        <div class="project-show-hero-img" style="background-image:url('{{ $project->featured_image_url }}');"></div>
                    @else
                        <div class="project-show-hero-img project-show-hero-img--placeholder">
                            <i class="bi bi-kanban-fill" aria-hidden="true"></i>
                        </div>
                    @endif

                    @if ($summary)
                        <p class="project-show-summary">{{ $summary }}</p>
                    @endif

                    @if ($pt?->body)
                        <div class="project-show-prose">{!! safe_html($pt->body) !!}</div>
                    @endif

                    @if (is_array($pt?->highlights) && count($pt->highlights) > 0)
                        <h3 class="project-show-highlights-title">{{ __('common.highlights') }}</h3>
                        <ul class="project-show-highlights">
                            @foreach ($pt->highlights as $item)
                                <li>
                                    <i class="bi bi-check-circle-fill" aria-hidden="true"></i>
                                    <span>{{ is_array($item) ? ($item['text'] ?? '') : $item }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>

                <div class="col-lg-4" data-aos="fade-left">
                    <div class="project-show-sidebar">
                        <h4 class="project-show-sidebar__label">{{ __('projects.show.project_details') }}</h4>
                        <dl class="project-show-sidebar__meta">
                            @if ($project->client_name)
                                <div>
                                    <dt>{{ __('projects.show.client') }}</dt>
                                    <dd>{{ $project->client_name }}</dd>
                                </div>
                            @endif
                            @if ($project->location)
                                <div>
                                    <dt>{{ __('projects.show.location') }}</dt>
                                    <dd>{{ $project->location }}</dd>
                                </div>
                            @endif
                            @if ($project->year)
                                <div>
                                    <dt>{{ __('projects.show.year') }}</dt>
                                    <dd>{{ $project->year }}</dd>
                                </div>
                            @endif
                        </dl>

                        @if ($project->services->isNotEmpty())
                            <div class="project-show-sidebar__services">
                                <p class="project-show-sidebar__label">{{ __('navigation.services') }}</p>
                                <ul>
                                    @foreach ($project->services as $service)
                                        @php $st = $service->translate($locale); @endphp
                                        @if ($st)
                                            <li>
                                                <a href="{{ route('services.show', [$locale, $st->slug]) }}">
                                                    <i class="{{ \App\Support\BootstrapIcon::classes($service->icon, 'bi-gear-fill') }}" aria-hidden="true"></i>{{ $st->title }}
                                                </a>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <a href="{{ route('contact', $locale) }}" class="btn-gold project-show-sidebar__cta">
                            <i class="bi bi-envelope-fill" aria-hidden="true"></i>
                            <span>{{ __('buttons.discuss_similar') ?? __('buttons.request_quote') }}</span>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </section>

@endsection
