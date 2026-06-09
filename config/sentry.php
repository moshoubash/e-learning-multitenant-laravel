<?php

return [
    'dsn' => env('SENTRY_LARAVEL_DSN'),
    'traces_sample_rate' => (float) env('SENTRY_TRACES_SAMPLE_RATE', 0.0),
    'profiles_sample_rate' => (float) env('SENTRY_PROFILES_SAMPLE_RATE', 0.0),
    'send_default_pii' => false,
    'environment' => env('SENTRY_ENVIRONMENT', app()->environment()),
    'release' => env('SENTRY_RELEASE'),
    'before_send' => null,
];
