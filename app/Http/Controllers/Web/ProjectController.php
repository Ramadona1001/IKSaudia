<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\LocaleService;
use App\Services\ProjectCatalogService;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function __construct(
        protected ProjectCatalogService $projects,
        protected LocaleService $locales,
    ) {}

    public function index(string $locale): View
    {
        return view('projects.index', [
            'projects' => $this->projects->publishedList($locale),
            'locales' => $this->locales->active(),
        ]);
    }

    public function show(string $locale, string $slug): View
    {
        $project = $this->projects->findPublishedBySlug($slug, $locale);

        abort_if($project === null, 404);

        return view('projects.show', [
            'project' => $project,
            'translation' => $project->translate($locale),
            'seo' => $project->seoFor($locale),
            'locales' => $this->locales->active(),
        ]);
    }
}
