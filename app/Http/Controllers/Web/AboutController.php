<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\CertificationCatalogService;
use App\Services\HomePageService;
use App\Services\LocaleService;
use Illuminate\View\View;

class AboutController extends Controller
{
    public function __construct(
        protected HomePageService $homePage,
        protected LocaleService $locales,
        protected CertificationCatalogService $certifications,
    ) {}

    public function __invoke(string $locale): View
    {
        return view('front.about', [
            'sections' => $this->homePage->sections($locale),
            'foundationSection' => $this->homePage->sectionByKey('foundation', $locale),
            'locales' => $this->locales->active(),
            'certifications' => $this->certifications->featured($locale, 8),
        ]);
    }
}
