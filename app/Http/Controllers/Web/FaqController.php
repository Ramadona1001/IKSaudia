<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\FaqService;
use App\Services\LocaleService;
use Illuminate\View\View;

class FaqController extends Controller
{
    public function __construct(
        protected FaqService $faqs,
        protected LocaleService $locales,
    ) {}

    public function __invoke(string $locale): View
    {
        return view('front.faq', [
            'categories' => $this->faqs->categories($locale),
            'locales' => $this->locales->active(),
        ]);
    }
}
