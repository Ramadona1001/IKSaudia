@props(['projects', 'translation' => null])

@php $locale = app()->getLocale(); @endphp

@if ($projects->isNotEmpty())
<section id="projects" class="section-padding relative overflow-hidden section-divider" x-data="projectsCarousel">
    <div class="absolute inset-0 bg-gradient-to-b from-navy-950 via-navy-900/90 to-navy-950" aria-hidden="true"></div>
    <div class="absolute inset-0 bg-industrial-grid opacity-25" aria-hidden="true"></div>

    <div class="container-iks relative">
        <div class="flex flex-col gap-8 lg:flex-row lg:items-end lg:justify-between reveal">
            <x-ui.section-heading
                :overline="__('home.projects.overline')"
                :title="$translation?->title ?? __('home.projects.default_title')"
                :subtitle="$translation?->subtitle ?? __('home.projects.default_subtitle')"
            />
            <div class="flex flex-wrap items-center gap-3 shrink-0">
                <x-ui.button :href="route('projects.index', $locale)" variant="outline" size="sm">
                    {{ __('buttons.all_projects') }}
                </x-ui.button>
                <div class="flex gap-2">
                    <button type="button" @click="prev()" class="flex h-11 w-11 items-center justify-center rounded-xl border border-white/15 text-steel-300 transition hover:border-accent hover:text-white hover:shadow-glow-sm" aria-label="{{ __('common.previous') }}">
                        <svg class="h-5 w-5 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    </button>
                    <button type="button" @click="next()" class="flex h-11 w-11 items-center justify-center rounded-xl border border-white/15 text-steel-300 transition hover:border-accent hover:text-white hover:shadow-glow-sm" aria-label="{{ __('common.next') }}">
                        <svg class="h-5 w-5 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </button>
                </div>
            </div>
        </div>

        <div class="mt-12 reveal">
            <div x-ref="track" class="flex gap-6 overflow-x-auto snap-x snap-mandatory pb-6 scrollbar-hide -mx-4 px-4 sm:mx-0 sm:px-0">
                @foreach ($projects as $project)
                    @php $pt = $project->translate($locale); @endphp
                    @if ($pt)
                        <article class="min-w-[88%] snap-center shrink-0 sm:min-w-[48%] lg:min-w-[36%]">
                            <x-ui.card :href="route('projects.show', [$locale, $pt->slug])" class="h-full">
                                <x-ui.project-image :project="$project" class="aspect-[16/10] mb-6 overflow-hidden rounded-xl" />
                                <div class="flex flex-wrap items-center gap-2 mb-4">
                                    @if ($project->year)
                                        <x-ui.badge variant="steel">{{ $project->year }}</x-ui.badge>
                                    @endif
                                    <x-ui.badge variant="accent">{{ __('common.project') }}</x-ui.badge>
                                </div>
                                <h3 class="text-xl font-bold text-white transition group-hover:text-accent">{{ $pt->title }}</h3>
                                @if ($pt->summary)
                                    <p class="mt-3 text-sm leading-relaxed text-steel-400 line-clamp-2">{{ $pt->summary }}</p>
                                @endif
                                @if ($project->client_name)
                                    <p class="mt-5 flex items-center gap-2 text-caption text-steel-500">
                                        <span class="h-px flex-1 bg-white/10"></span>
                                        {{ $project->client_name }}
                                    </p>
                                @endif
                                <span class="mt-6 inline-flex items-center gap-2 text-sm font-semibold text-accent opacity-0 transition duration-300 group-hover:opacity-100">
                                    {{ __('buttons.view_project') }}
                                    <svg class="h-4 w-4 rtl:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                                </span>
                            </x-ui.card>
                        </article>
                    @endif
                @endforeach
            </div>

            @if ($projects->count() > 1)
                <div class="mt-8 flex justify-center gap-2" role="tablist" aria-label="{{ __('home.projects.navigation') }}">
                    @foreach ($projects as $i => $project)
                        <button
                            type="button"
                            @click="goTo({{ $i }})"
                            class="h-1.5 rounded-full transition-all duration-300"
                            :class="index === {{ $i }} ? 'w-8 bg-accent' : 'w-2 bg-white/20 hover:bg-white/40'"
                            :aria-label="__('home.projects.slide_label', ['number' => $i + 1])"
                        ></button>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</section>
@endif
