<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\LocaleService;
use App\Services\ServiceCatalogService;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function __construct(
        protected ServiceCatalogService $services,
        protected LocaleService $locales,
    ) {}

    public function index(string $locale): View
    {
        return view('front.services.index', [
            'services' => $this->services->publishedList($locale),
            'locales' => $this->locales->active(),
        ]);
    }

    public function show(string $locale, string $slug): View
    {
        $service = $this->services->findPublishedBySlug($slug, $locale);

        abort_if($service === null, 404);

        $related = \App\Models\Service::query()
            ->published()
            ->whereKeyNot($service->getKey())
            ->with(['translations' => fn ($q) => $q->where('locale', $locale)])
            ->orderBy('sort_order')
            ->limit(5)
            ->get();

        return view('front.services.show', [
            'service' => $service,
            'translation' => $service->translate($locale),
            'seo' => $service->seoFor($locale),
            'related' => $related,
            'locales' => $this->locales->active(),
        ]);
    }
}
