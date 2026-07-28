<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\CertificationCatalogService;
use App\Services\ClientCatalogService;
use App\Services\FaqService;
use App\Services\HomePageService;
use App\Services\IndustryCatalogService;
use App\Services\LocaleService;
use App\Services\PartnerCatalogService;
use App\Services\ProjectCatalogService;
use App\Services\ServiceCatalogService;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __construct(
        protected HomePageService $homePage,
        protected LocaleService $locales,
        protected ServiceCatalogService $services,
        protected ProjectCatalogService $projects,
        protected IndustryCatalogService $industries,
        protected CertificationCatalogService $certifications,
        protected PartnerCatalogService $partners,
        protected ClientCatalogService $clients,
        protected FaqService $faqs,
    ) {}

    public function __invoke(string $locale): View
    {
        return view('front.home', [
            'sections' => $this->homePage->sections($locale),
            'foundationSection' => $this->homePage->sectionByKey('foundation', $locale),
            'locales' => $this->locales->active(),
            'featuredServices' => $this->services->featured($locale, 6),
            'featuredProjects' => $this->projects->featured($locale, 6),
            'featuredIndustries' => $this->industries->featured($locale, 6),
            'featuredCertifications' => $this->certifications->featured($locale, 8),
            'publishedPartners' => $this->partners->publishedList($locale),
            'publishedClients' => $this->clients->publishedList($locale),
            'homeFaqs' => $this->faqs->flat($locale, 6),
        ]);
    }
}
