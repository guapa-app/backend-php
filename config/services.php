<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'fcm' => [
        'key' => env('FCM_SERVER_KEY'),
    ],

    'firebase' => [
        'credentials' => env('FIREBASE_CREDENTIALS'),
    ],

    'connectly' => [
        'url' => env('CONNECTLY_API_URL'),
        'key' => env('CONNECTLY_API_KEY'),
        'business_id' => env('CONNECTLY_BUSINESS_ID'),
    ],

    'connectsaudi' => [
        'api_url' => env('CONNECT_SAUDI_API_URL'),
        'user' => env('CONNECT_SAUDI_API_USER'),
        'password' => env('CONNECT_SAUDI_API_PASSWORD'),
        'sender_id' => env('CONNECT_SAUDI_API_SENDERID'),
    ],

    'zoom' => [
        'key' => env('ZOOM_API_KEY'),
        'secret' => env('ZOOM_API_SECRET'),
        'user_id' => env('ZOOM_USER_ID'),
    ],

    'meetings' => [
        'default_provider' => env('DEFAULT_MEETING_PROVIDER', 'zoom'),
    ],

    'external_notification' => [
        'endpoint' => env('EXTERNAL_NOTIFICATION_ENDPOINT', 'https://notification-service.example.com/api/notifications'),
        'token' => env('EXTERNAL_NOTIFICATION_TOKEN'),
        'timeout' => env('EXTERNAL_NOTIFICATION_TIMEOUT', 30),
        'retry_attempts' => env('EXTERNAL_NOTIFICATION_RETRY_ATTEMPTS', 3),
        'retry_delay' => env('EXTERNAL_NOTIFICATION_RETRY_DELAY', 1000), // milliseconds
        'verify_ssl' => env('EXTERNAL_NOTIFICATION_VERIFY_SSL', true),
        'app_id' => env('EXTERNAL_NOTIFICATION_APP_ID', 'guapa-laravel'),
        'secret_key' => env('EXTERNAL_NOTIFICATION_SECRET_KEY'),
    ],

];
