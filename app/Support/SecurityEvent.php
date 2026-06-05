<?php

namespace App\Support;

use Illuminate\Support\Facades\Log;

/**
 * Centralized security event logger.
 *
 * Emits a single-line JSON record per event with stable keys so a log
 * pipeline (Loki, CloudWatch, Datadog, etc.) can alert on them.
 *
 * Mitigates OWASP A09:2021 - Security Logging and Monitoring Failures.
 */
class SecurityEvent
{
    public const LOGIN_SUCCESS = 'auth.login.success';
    public const LOGIN_FAILURE = 'auth.login.failure';
    public const LOGIN_LOCKOUT = 'auth.login.lockout';
    public const LOGOUT = 'auth.logout';
    public const PASSWORD_RESET_REQUEST = 'auth.password_reset.request';
    public const PASSWORD_RESET_SUCCESS = 'auth.password_reset.success';
    public const ACCESS_DENIED = 'authz.access_denied';
    public const SUSPICIOUS_REQUEST = 'http.suspicious';

    public static function log(string $event, array $context = []): void
    {
        $record = array_merge([
            'event' => $event,
            'ip' => request()?->ip(),
            'user_agent' => request()?->userAgent(),
            'user_id' => auth()->id(),
            'tenant_id' => tenant()?->getTenantKey(),
            'at' => now()->toIso8601String(),
        ], $context);

        Log::channel('security')->info('security_event', $record);
    }
}
