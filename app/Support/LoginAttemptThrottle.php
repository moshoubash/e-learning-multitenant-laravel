<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;

/**
 * Track failed login attempts per email and lock the account after a
 * threshold. Used by the login flow to refuse credentials while a lock
 * is active.
 *
 * Mitigates OWASP A04:2021 - Insecure Design (account lockout).
 */
class LoginAttemptThrottle
{
    private const MAX_ATTEMPTS = 5;
    private const DECAY_MINUTES = 15;
    private const LOCKOUT_KEY = 'login_lockout:';

    public static function tooManyAttempts(string $email): bool
    {
        return Cache::has(self::LOCKOUT_KEY . strtolower($email));
    }

    public static function hit(string $email): int
    {
        $key = 'login_attempts:' . strtolower($email);
        $attempts = (int) Cache::get($key, 0) + 1;

        Cache::put($key, $attempts, now()->addMinutes(self::DECAY_MINUTES));

        if ($attempts >= self::MAX_ATTEMPTS) {
            Cache::put(
                self::LOCKOUT_KEY . strtolower($email),
                true,
                now()->addMinutes(self::DECAY_MINUTES)
            );
        }

        return $attempts;
    }

    public static function clear(string $email): void
    {
        Cache::forget('login_attempts:' . strtolower($email));
        Cache::forget(self::LOCKOUT_KEY . strtolower($email));
    }

    public static function secondsUntilUnlock(string $email): int
    {
        return (int) Cache::get(
            self::LOCKOUT_KEY . strtolower($email) . ':until',
            0
        );
    }
}
