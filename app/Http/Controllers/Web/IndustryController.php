<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Industry;
use App\Services\IndustryCatalogService;
use App\Services\LocaleService;
use Illuminate\View\View;

class IndustryController extends Controller
{
    public function __construct(
        protected IndustryCatalogService $industries,
        protected LocaleService $locales,
    ) {}

    public function index(string $locale): View
    {
        return view('front.industries.index', [
            'industries' => $this->industries->publishedList($locale),
            'locales' => $this->locales->active(),
        ]);
    }

    public function show(string $locale, string $slug): View
    {
        $industry = $this->industries->findPublishedBySlug($slug, $locale);

        abort_if($industry === null, 404);

        $related = Industry::query()
            ->published()
            ->whereKeyNot($industry->getKey())
            ->with(['translations' => fn ($q) => $q->where('locale', $locale)])
            ->orderBy('sort_order')
            ->limit(4)
            ->get();

        return view('front.industries.show', [
            'industry' => $industry,
            'translation' => $industry->translate($locale),
            'seo' => $industry->seoFor($locale),
            'related' => $related,
            'locales' => $this->locales->active(),
        ]);
    }
}
