<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Refuse to boot if APP_KEY is missing or appears to be the well-known
 * default placeholder value. APP_KEY is the secret used to encrypt
 * sessions, cookies, signed URLs and any Crypt::encryptString() payloads.
 *
 * Without a real key, encrypted data is forgeable.
 *
 * Mitigates OWASP A02:2021 - Cryptographic Failures.
 */
class EnsureAppKeyIsStrong
{
    private const PLACEHOLDERS = [
        '',
        'base64:',
        'ChangeMe',
        'SomeRandomString',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $key = config('app.key');

        if (! is_string($key) || strlen($key) < 16) {
            abort(500, 'APP_KEY is not configured. Run `php artisan key:generate`.');
        }

        foreach (self::PLACEHOLDERS as $bad) {
            if ($key === $bad || str_starts_with($key, $bad)) {
                abort(500, 'APP_KEY appears to be a placeholder. Run `php artisan key:generate`.');
            }
        }

        return $next($request);
    }
}
