@props([
    'project',
    'delay' => 0,
])

@php
    $locale = app()->getLocale();
    $pt = $project->translate($locale);

    if (! $pt) {
        return;
    }

    $href = route('projects.show', [$locale, $pt->slug]);
@endphp

<a href="{{ $href }}"
   {{ $attributes->merge(['class' => 'service-card project-card']) }}
   data-aos="fade-up"
   data-aos-delay="{{ $delay }}"
   aria-label="{{ $pt->title }}">
    @if ($project->featured_image_url)
        <div class="project-card__media" style="background-image:url('{{ $project->featured_image_url }}');"></div>
    @else
        <div class="project-card__media project-card__media--placeholder">
            <i class="bi bi-kanban-fill" aria-hidden="true"></i>
        </div>
    @endif

    <div class="service-body">
        @if ($project->year || $project->location)
            <div class="project-card__meta">
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
            <p class="project-card__client">{{ $project->client_name }}</p>
        @endif
    </div>
</a>
