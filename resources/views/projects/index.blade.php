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

    <section class="section-pad projects-index-section">
        <div class="container">
            <div class="row g-4">
                @forelse ($projects as $project)
                    @php $pt = $project->translate($locale); @endphp
                    @if ($pt)
                        <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ ($loop->index % 3) * 100 }}">
                            <x-front.project-card :project="$project" />
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
