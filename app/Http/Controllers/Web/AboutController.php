<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\CertificationCatalogService;
use App\Services\LocaleService;
use Illuminate\View\View;

class AboutController extends Controller
{
    public function __construct(
        protected LocaleService $locales,
        protected CertificationCatalogService $certifications,
    ) {}

    public function __invoke(string $locale): View
    {
        return view('front.about', [
            'locales' => $this->locales->active(),
            'certifications' => $this->certifications->featured($locale, 8),
        ]);
    }
}
