<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    'allowed_origins'  => [
        'https://4c88-2603-90d8-401-714b-9005-4c3a-c12e-31dc.ngrok-free.app/',          // dev tunnel
        'https://b036-2603-90d8-401-714b-9005-4c3a-c12e-31dc.ngrok-free.app/',
        // 'https://api.yourdomain.com',      // production API
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'allowed_headers'  => ['Content-Type', 'Authorization', 'X-Requested-With'],
    'supports_credentials' => true,

];
