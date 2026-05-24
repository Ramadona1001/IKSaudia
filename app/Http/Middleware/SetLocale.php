<?php

namespace App\Http\Middleware;

use App\Services\LocaleService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    public function __construct(
        protected LocaleService $locales,
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->route('locale');

        if (! is_string($locale) || ! $this->locales->isSupported($locale)) {
            abort(404);
        }

        app()->setLocale($locale);
        $request->attributes->set('locale', $locale);
        $request->attributes->set('text_direction', $this->locales->direction($locale));

        return $next($request);
    }
}
