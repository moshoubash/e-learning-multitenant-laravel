<?php

return [
    'enabled' => env('TELESCOPE_ENABLED', env('APP_ENV', 'production') !== 'production'),
    'domain' => env('TELESCOPE_DOMAIN'),
    'path' => env('TELESCOPE_PATH', 'telescope'),
    'middleware' => [
        'web',
    ],
    'storage' => [
        'driver' => env('TELESCOPE_STORAGE_DRIVER', 'database'),
        'connection' => env('TELESCOPE_DB_CONNECTION', env('DB_CONNECTION', 'sqlite')),
        'chunk' => 1000,
    ],
    'queue' => [
        'connection' => env('TELESCOPE_QUEUE_CONNECTION'),
        'queue' => env('TELESCOPE_QUEUE'),
    ],
    'watchers' => [],
];
