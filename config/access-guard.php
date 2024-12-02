<?php

return [
    // Token expiration in minutes (default: 7 days)
    'session_token_expiry' => now()->addDays(7), // 7 days from now
    'otp_expires_in_minutes' => 10,

    // Rate-limiting settings
    'rate_limit' => [
        'requests' => env('ACCESS_GUARD_RATE_LIMIT_REQUESTS', 5), // Max attempts per minute
        'reset_interval' => env('ACCESS_GUARD_RATE_LIMIT_RESET_INTERVAL', 1), // Reset time in minutes
    ],

    // Session configuration for Access Guard
    'session_driver' =>  env('ACCESS_GUARD_SESSION_DRIVER', 'file'),

    // Notification email settings
    'notifications' => [
        'recipient_emails' => env('ACCESS_GUARD_NOTIFICATION_EMAILS', ''), // Comma-separated list of recipient email addresses example : admin@example.com,user@example.com
        'is_errors_notifications_enabled' => env('ACCESS_GUARD_ERROR_NOTIFICATIONS_ENABLED', false), // Enable or disable error notifications
        'is_verified_notifications_enabled' => env('ACCESS_GUARD_VERIFY_EMAIL_ENABLED', false), // Enable or disable email verification notifications
    ],

    'auth' => [
        'username' => env('ACCESS_GUARD_USERNAME', 'admin'),
        'password' => env('ACCESS_GUARD_PASSWORD', 'secret'),
    ],
];
