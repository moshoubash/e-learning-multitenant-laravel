<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Add the HTTP response headers recommended by OWASP Secure Headers
 * and Mozilla Observatory to every response.
 *
 * Mitigates OWASP A05:2021 - Security Misconfiguration.
 */
class SecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-XSS-Protection', '0');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');

        $isProduction = app()->environment('production');

        $csp = $isProduction
            ? "default-src 'self'; script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://maxst.icons8.com; style-src 'self' 'unsafe-inline' https://fonts.bunny.net https://maxst.icons8.com; font-src 'self' https://fonts.bunny.net data:; img-src 'self' data: blob: https:; connect-src 'self'; frame-ancestors 'self'; base-uri 'self'; form-action 'self';"
            : "default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net https://maxst.icons8.com; style-src 'self' 'unsafe-inline' https://fonts.bunny.net https://maxst.icons8.com; font-src 'self' https://fonts.bunny.net data:; img-src 'self' data: blob: https:; connect-src 'self' ws: wss:; frame-ancestors 'self'; base-uri 'self'; form-action 'self';";

        $response->headers->set('Content-Security-Policy', $csp);

        if ($isProduction) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        return $response;
    }
}
