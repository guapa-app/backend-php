<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Gift Card Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains configuration options for the gift card system.
    |
    */

    // Background colors available for gift cards
    'colors' => [
        '#FF8B85', // Light Red
        '#FFB3BA', // Light Pink
        '#FFD3B6', // Light Orange
        '#FFEFD1', // Light Yellow
        '#DCEDC8', // Light Green
        '#B2DFDB', // Light Teal
        '#B3E5FC', // Light Blue
        '#E1BEE7', // Light Purple
        '#F8BBD9', // Pink
        '#C8E6C9', // Green
        '#BBDEFB', // Blue
        '#D1C4E9', // Purple
    ],

    // Suggested amounts for quick selection
    'suggested_amounts' => [
        50,
        100,
        200,
        500,
        1000,
    ],

    // Gift card code settings
    'code_length' => 8,
    'code_prefix' => 'GC',

    // Default expiration settings
    'expiration_days' => 365, // Default expiration in days

    // File upload settings
    'max_file_size' => 5242880, // 5MB in bytes
    'allowed_file_types' => [
        'image/jpeg',
        'image/png',
        'image/gif',
        'image/svg+xml',
    ],

    // Currency settings
    'default_currency' => 'SAR',
    'supported_currencies' => [
        'SAR' => 'Saudi Riyal',
        'USD' => 'US Dollar',
        'EUR' => 'Euro',
    ],

    // Minimum and maximum amounts
    'min_amount' => 1,
    'max_amount' => 10000,

    'background_images' => [
        // Add your image URLs or storage paths here
        '/images/giftcards/bg1.png',
        '/images/giftcards/bg2.jpg',
        '/images/giftcards/bg3.svg',
    ],
];
