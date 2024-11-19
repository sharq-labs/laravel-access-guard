<?php

return [
    // Token expiration in minutes (default: 7 days)
    'session_token_expiry' => now()->addDays(7), // 7 days from now
    'otp_expires_in_minutes' => 10,
    'otp_request_min_interval' => 1,
    // Rate-limiting settings
    'rate_limit' => [
        'requests' => env('ACCESS_GUARD_RATE_LIMIT_REQUESTS', 5), // Max attempts per minute
        'reset_interval' => env('ACCESS_GUARD_RATE_LIMIT_RESET_INTERVAL', 1), // Reset time in minutes
    ],

    // Session configuration for Access Guard
    'session' => [
        'driver' => 'file',
        'cookie' => 'access_guard_session',
        'lifetime' => 60,
        'files' => storage_path('framework/sessions/access-guard'),
    ],
];
