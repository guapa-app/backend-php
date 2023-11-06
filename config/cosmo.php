<?php

return [

    // Api versions
    'api_version' => env('API_VERSION', 'v1'),
    'admin_api_version' => env('ADMIN_API_VERSION', 'v1'),

    // personal client
    'personal_client_id' => env('PERSONAL_CLIENT_ID', 1),
    'personal_client_key' => env('PERSONAL_CLIENT_SECRET', ''),

    // password client
    'password_client_id' => env('PASSWORD_CLIENT_ID', 2),
    'password_client_secret' => env('PASSWORD_CLIENT_SECRET', ''),

    // password client
    'admin_client_id' => env('ADMIN_CLIENT_ID', 3),
    'admin_client_secret' => env('ADMIN_CLIENT_SECRET', ''),

    'firebase_project_id' => env('FIREBASE_PROJECT_ID', ''),

    'admin_password' => env('ADMIN_PASSWORD', '123456789'),
    'admin_email' => env('ADMIN_EMAIL', 'admin@cosmo.com'),

    'sinch_username' => env('SINCH_USERNAME', ''),
    'sinch_password' => env('SINCH_PASSWORD', ''),

    'home_view' => 'welcome',
    'admin_view' => 'admin',
];
