<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\LocaleService;
use App\Services\PageService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PageController extends Controller
{
    public function __construct(
        protected PageService $pages,
        protected LocaleService $locales,
    ) {}

    public function show(Request $request, string $locale, string $slug): View
    {
        $page = $this->pages->findPublishedBySlug($slug, $locale);

        abort_if($page === null, 404);

        $translation = $page->translate($locale);

        return view('pages.show', [
            'page' => $page,
            'translation' => $translation,
            'seo' => $page->seoFor($locale),
            'locales' => $this->locales->active(),
        ]);
    }
}
