<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $headers = config('security.headers', []);

        $response->headers->set('X-Frame-Options', $headers['x_frame_options'] ?? 'SAMEORIGIN');
        $response->headers->set('X-Content-Type-Options', $headers['x_content_type_options'] ?? 'nosniff');
        $response->headers->set('Referrer-Policy', $headers['referrer_policy'] ?? 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', $headers['permissions_policy'] ?? 'camera=(), microphone=(), geolocation=()');
        $response->headers->set('Cross-Origin-Opener-Policy', $headers['cross_origin_opener_policy'] ?? 'same-origin');
        $response->headers->set('Cross-Origin-Resource-Policy', $headers['cross_origin_resource_policy'] ?? 'same-site');
        $response->headers->set('X-Permitted-Cross-Domain-Policies', 'none');

        if ($this->shouldSendHsts($request)) {
            $hsts = config('security.hsts', []);
            $value = 'max-age='.($hsts['max_age'] ?? 31536000);
            if ($hsts['include_subdomains'] ?? true) {
                $value .= '; includeSubDomains';
            }
            if ($hsts['preload'] ?? false) {
                $value .= '; preload';
            }
            $response->headers->set('Strict-Transport-Security', $value);
        }

        $this->applyContentSecurityPolicy($request, $response);

        return $response;
    }

    protected function shouldSendHsts(Request $request): bool
    {
        if (! config('security.hsts.enabled', true)) {
            return false;
        }

        return $request->secure() || app()->environment('production');
    }

    protected function applyContentSecurityPolicy(Request $request, Response $response): void
    {
        if (! config('security.csp.enabled', true)) {
            return;
        }

        $adminPath = trim(config('cms.admin_path', 'ik-admin'), '/');
        $isAdmin = $request->is($adminPath) || $request->is($adminPath.'/*');
        $directives = config($isAdmin ? 'security.csp.admin' : 'security.csp.public', []);

        if (empty($directives)) {
            return;
        }

        $parts = [];
        foreach ($directives as $name => $sources) {
            if ($sources === []) {
                $parts[] = $name;
            } else {
                $parts[] = $name.' '.implode(' ', $sources);
            }
        }

        $header = config('security.csp.report_only', true)
            ? 'Content-Security-Policy-Report-Only'
            : 'Content-Security-Policy';

        $policy = implode('; ', $parts);

        if ($uri = config('security.csp.report_uri')) {
            $policy .= '; report-uri '.$uri;
        }

        $response->headers->set($header, $policy);
    }
}
