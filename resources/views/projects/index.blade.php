@extends('front.layouts.app')

@php $locale = app()->getLocale(); @endphp

@section('title', __('navigation.projects'))
@section('meta_description', __('projects.index.subtitle'))

@section('content')

    <x-front.page-hero
        :tag="__('projects.index.overline')"
        icon="bi-kanban-fill"
        :title="__('projects.index.title')"
        :subtitle="__('projects.index.subtitle')"
    />

    <x-front.breadcrumb :items="[['label' => __('navigation.projects')]]" />

    <section class="section-pad bg-dark1">
        <div class="container">
            <div class="row g-4">
                @forelse ($projects as $project)
                    @php $pt = $project->translate($locale); @endphp
                    @if ($pt)
                        <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ ($loop->index % 3) * 100 }}">
                            <a href="{{ route('projects.show', [$locale, $pt->slug]) }}" class="service-card" style="display:flex;flex-direction:column;height:100%;overflow:hidden;border-top:3px solid var(--c-gold);">
                                @if ($project->featured_image_url)
                                    <div style="height:220px;background-image:url('{{ $project->featured_image_url }}');background-size:cover;background-position:center;"></div>
                                @else
                                    <div style="height:220px;background:linear-gradient(135deg,#0d2137 0%,#1a3a5c 40%,#0a1020 100%);display:flex;align-items:center;justify-content:center;">
                                        <i class="bi bi-kanban-fill" style="font-size:4rem;color:rgba(201,162,39,0.15);" aria-hidden="true"></i>
                                    </div>
                                @endif
                                <div class="service-body">
                                    @if ($project->year || $project->location)
                                        <div style="font-size:0.72rem;font-weight:700;letter-spacing:2px;text-transform:uppercase;color:var(--c-gold);margin-bottom:8px;">
                                            @if ($project->year){{ $project->year }}@endif
                                            @if ($project->year && $project->location) · @endif
                                            @if ($project->location){{ $project->location }}@endif
                                        </div>
                                    @endif
                                    <h3 class="service-title">{{ $pt->title }}</h3>
                                    @if ($pt->summary)
                                        <p class="service-desc">{{ \Illuminate\Support\Str::limit($pt->summary, 140) }}</p>
                                    @endif
                                    @if ($project->client_name)
                                        <p style="font-size:0.78rem;color:var(--c-muted);margin-top:14px;">{{ $project->client_name }}</p>
                                    @endif
                                </div>
                            </a>
                        </div>
                    @endif
                @empty
                    <div class="col-12">
                        <p class="text-center" style="color:var(--c-muted);padding:60px 0;">{{ __('projects.index.empty') }}</p>
                    </div>
                @endforelse
            </div>

            @if ($projects instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator && $projects->hasPages())
                <div class="mt-5">{{ $projects->withQueryString()->links() }}</div>
            @endif
        </div>
    </section>

    <x-front.cta-section :title="__('front.industries.cta_title')" :description="__('front.industries.cta_desc')">
        <a href="{{ route('contact', $locale) }}" class="btn-gold">
            <i class="bi bi-chat-dots-fill" aria-hidden="true"></i>
            <span>{{ __('buttons.contact_us') }}</span>
        </a>
    </x-front.cta-section>

@endsection
