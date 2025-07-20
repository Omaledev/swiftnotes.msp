<?php

return [
    'paths' => [
        'api/*',
        'sanctum/csrf-cookie',
        'login',
        'logout',
        'broadcasting/auth', // Critical for Pusher
        'user-online'       // custom endpoint
    ],

    'allowed_methods' => ['*'],

    'allowed_origins' => [
        'http://127.0.0.1:8000', // frontend URL
        'http://localhost:8000'
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true, // Must be true for Sanctum
];
