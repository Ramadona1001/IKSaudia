<?php

namespace App\Http\Middleware;

use App\Services\NavigationService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureNavigationRouteVisible
{
    /**
     * Routes that stay reachable even when not listed in the header menu.
     *
     * @var list<string>
     */
    private const ALWAYS_ACCESSIBLE = [
        'home',
        'search',
    ];

    public function __construct(
        protected NavigationService $navigation,
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        $route = $request->route();

        if ($route === null) {
            return $next($request);
        }

        $routeName = $route->getName();

        if ($routeName === null || in_array($routeName, self::ALWAYS_ACCESSIBLE, true)) {
            return $next($request);
        }

        if (! $this->navigation->isRouteAccessible($routeName, $route->parameters())) {
            abort(404);
        }

        return $next($request);
    }
}
