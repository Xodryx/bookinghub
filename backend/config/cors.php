<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Configured for Sanctum SPA cookie authentication: the Angular frontend
    | must be an allowed origin and credentials (cookies) must be supported.
    |
    */

    'paths' => ['api/*', 'login', 'logout', 'register', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    'allowed_origins' => [env('FRONTEND_URL', 'http://localhost:4200')],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    // Required so the browser sends/receives the session and XSRF cookies.
    'supports_credentials' => true,

];
