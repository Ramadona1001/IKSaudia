@extends('layouts.app')

@section('title', __('home.meta_title'))

@section('content')
    @php
        $locale = app()->getLocale();
        $heroSection = $sections->firstWhere('type', 'hero');
        $aboutSection = $sections->firstWhere('type', 'about_snippet');
        $servicesSection = $sections->firstWhere('type', 'services_grid');
        $cmsExclude = ['hero', 'about_snippet', 'services_grid'];
    @endphp

    <div class="home-page">
        {{-- 1. Cinematic hero + animated industrial background --}}
        <x-home.hero
            :slides="$heroSection?->items ?? collect()"
            :settings="$heroSection?->settings ?? []"
            :translation="$heroSection?->translate($locale)"
        />

        {{-- 2. Statistics --}}
        <x-home.stats-band />

        {{-- 3. About (brand story) --}}
        <x-home.about-premium :translation="$aboutSection?->translate($locale)" :section="$aboutSection" />

        {{-- 4. Services showcase (interactive tabs) --}}
        <x-home.services-showcase
            :services="$featuredServices"
            :translation="$servicesSection?->translate($locale)"
        />

        {{-- 5. Mid-page CTA --}}
        <x-home.cta-section variant="inline" />

        {{-- 6. Industries --}}
        <x-home.industries :industries="$featuredIndustries" />

        {{-- 7. Process timeline --}}
        <x-home.process-timeline />

        {{-- 8. Projects carousel --}}
        <x-home.projects-showcase :projects="$featuredProjects" />

        {{-- 9. Certifications & trust --}}
        <x-home.certifications :certifications="$featuredCertifications" />

        {{-- 10. Primary conversion CTA --}}
        <x-home.cta-section variant="primary" />

        {{-- 11. Additional CMS homepage sections --}}
        @foreach ($sections->whereNotIn('type', $cmsExclude) as $section)
            @php $t = $section->translate($locale); @endphp
            @includeFirst(['sections.'.$section->type, 'sections.default'], [
                'section' => $section,
                'translation' => $t,
            ])
        @endforeach
    </div>
@endsection
