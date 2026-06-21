<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Redirect all non-HTTPS requests to their HTTPS equivalent in production.
 *
 * Skips:
 *  - non-production environments (local/testing)
 *  - health/ping endpoints (lets load balancers probe HTTP)
 *  - requests already marked as secure (proxies / load balancers)
 *
 * Mitigates OWASP A02:2021 - Cryptographic Failures by ensuring all
 * traffic in production is encrypted in transit.
 */
class ForceHttpsInProduction
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! app()->environment('production')) {
            return $next($request);
        }

        if (! env('FORCE_HTTPS', true)) {
            return $next($request);
        }

        if ($request->is('up', 'health*')) {
            return $next($request);
        }

        if ($request->isSecure() || $request->header('X-Forwarded-Proto') === 'https') {
            return $next($request);
        }

        return redirect()->secure($request->getRequestUri(), 301);
    }
}
