<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\ClientCatalogService;
use App\Services\LocaleService;
use Illuminate\View\View;

class ClientController extends Controller
{
    public function __construct(
        protected ClientCatalogService $clients,
        protected LocaleService $locales,
    ) {}

    public function __invoke(string $locale): View
    {
        return view('front.clients', [
            'clients' => $this->clients->publishedList($locale),
            'locales' => $this->locales->active(),
        ]);
    }
}
