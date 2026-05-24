<?php

namespace App\Http\Middleware;

use App\Services\LocaleService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;

class ResolveWebLocale
{
    public function __construct(
        protected LocaleService $locales,
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->query('locale');

        if (! is_string($locale) || ! $this->locales->isSupported($locale)) {
            $cookie = $request->cookie('locale');
            $locale = is_string($cookie) && $this->locales->isSupported($cookie)
                ? $cookie
                : null;
        }

        if ($locale === null) {
            $preferred = $request->getPreferredLanguage($this->locales->active()->pluck('code')->all());
            $locale = $this->locales->isSupported($preferred ?? '')
                ? $preferred
                : $this->locales->default();
        }

        app()->setLocale($locale);
        $request->attributes->set('locale', $locale);
        $request->attributes->set('text_direction', $this->locales->direction($locale));

        $response = $next($request);

        $response->headers->setCookie(Cookie::create(
            'locale',
            $locale,
            now()->addYear(),
            '/',
            null,
            $request->isSecure(),
            true,
            false,
            Cookie::SAMESITE_LAX,
        ));

        return $response;
    }
}
