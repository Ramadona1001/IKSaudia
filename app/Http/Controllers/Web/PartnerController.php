<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\CertificationCatalogService;
use App\Services\LocaleService;
use App\Services\PartnerCatalogService;
use Illuminate\View\View;

class PartnerController extends Controller
{
    public function __construct(
        protected PartnerCatalogService $partners,
        protected LocaleService $locales,
        protected CertificationCatalogService $certifications,
    ) {}

    public function __invoke(string $locale): View
    {
        return view('front.partners', [
            'partners' => $this->partners->publishedList($locale),
            'certifications' => $this->certifications->publishedList($locale),
            'locales' => $this->locales->active(),
        ]);
    }
}
