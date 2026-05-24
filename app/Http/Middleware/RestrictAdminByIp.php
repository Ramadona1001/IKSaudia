<?php

namespace App\Http\Middleware;

use App\Services\Security\SecurityEventLogger;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RestrictAdminByIp
{
    public function __construct(
        protected SecurityEventLogger $security,
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        $allowlist = config('security.admin.ip_allowlist', []);

        if ($allowlist === [] || ! config('security.admin.ip_allowlist_enabled', false)) {
            return $next($request);
        }

        $ip = $request->ip();

        if (! in_array($ip, $allowlist, true)) {
            $this->security->adminAccessDenied($request, 'ip_not_allowlisted');

            abort(403, 'Admin access is restricted from this network.');
        }

        return $next($request);
    }
}
