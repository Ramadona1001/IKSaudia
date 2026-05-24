<?php

namespace App\Http\Middleware;

use App\Models\Redirect;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class RedirectLegacyUrls
{
    public function handle(Request $request, Closure $next): Response
    {
        $path = '/'.ltrim(rawurldecode(parse_url($request->getRequestUri(), PHP_URL_PATH) ?? ''), '/');

        if ($path === '/' || str_starts_with($path, '/ar') || str_starts_with($path, '/en')) {
            return $next($request);
        }

        $redirect = Cache::remember(
            'redirect:'.md5($path),
            3600,
            fn () => Redirect::query()
                ->where('is_active', true)
                ->where('from_path', $path)
                ->first()
        );

        if ($redirect) {
            $redirect->increment('hits');
            $redirect->update(['last_hit_at' => now()]);

            return redirect($redirect->to_path, $redirect->status_code);
        }

        return $next($request);
    }
}
