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
    /**
     * Hosts / schemes the Vite dev server may run on. These must be
     * allowed in the dev CSP or the browser blocks the @vite/client
     * script, the live-reload WebSocket, and the CSS/JS bundles.
     *
     * NOTE: Vite's default IPv6 binding produces URLs like
     * 'http://[::]:5173' which are NOT valid in a CSP source list
     * (the spec rejects IPv6 in brackets). We force Vite to bind
     * to 'localhost' via vite.config.js so this list only needs the
     * IPv4 forms.
     */
    private const VITE_DEV_HOSTS = [
        'http://localhost:5173',
        'http://127.0.0.1:5173',
        'ws://localhost:5173',
        'ws://127.0.0.1:5173',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-XSS-Protection', '0');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');

        $response->headers->set('Content-Security-Policy', $this->buildCsp());

        if (app()->environment('production')) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        return $response;
    }

    private function buildCsp(): string
    {
        $viteHosts = implode(' ', self::VITE_DEV_HOSTS);

        if (app()->environment('production')) {
            return "default-src 'self'; "
                . "script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://maxst.icons8.com; "
                . "style-src 'self' 'unsafe-inline' https://fonts.bunny.net https://maxst.icons8.com; "
                . "font-src 'self' https://fonts.bunny.net https://maxst.icons8.com data:; "
                . "img-src 'self' data: blob: https:; "
                . "media-src 'self' https:; "
                . "frame-src 'self' https://www.youtube.com https://player.vimeo.com; "
                . "connect-src 'self'; "
                . "frame-ancestors 'self'; base-uri 'self'; form-action 'self';";
        }

        // Development: allow Vite HMR + the unsafe-eval that some
        // dev tools (Vue/React devtools, Alpine x-data expressions)
        // need. Without these, npm run dev shows an unstyled page.
        return "default-src 'self'; "
            . "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net https://maxst.icons8.com {$viteHosts}; "
            . "style-src 'self' 'unsafe-inline' https://fonts.bunny.net https://maxst.icons8.com {$viteHosts}; "
            . "font-src 'self' https://fonts.bunny.net https://maxst.icons8.com data:; "
            . "img-src 'self' data: blob: https: {$viteHosts}; "
            . "media-src 'self' https: {$viteHosts}; "
            . "frame-src 'self' https://www.youtube.com https://player.vimeo.com {$viteHosts}; "
            . "connect-src 'self' ws: wss: {$viteHosts}; "
            . "frame-ancestors 'self'; base-uri 'self'; form-action 'self';";
    }
}
