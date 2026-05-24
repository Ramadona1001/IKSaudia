<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\LocaleService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LocaleRedirectController extends Controller
{
    public function __construct(
        protected LocaleService $locales,
    ) {}

    public function __invoke(Request $request): RedirectResponse
    {
        $preferred = $request->getPreferredLanguage($this->locales->active()->pluck('code')->all());

        $locale = $this->locales->isSupported($preferred ?? '')
            ? $preferred
            : $this->locales->default();

        return redirect()->route('home', ['locale' => $locale]);
    }
}
