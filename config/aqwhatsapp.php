<?php

// config for AQuadic/AQWhatsapp
return [

    /*
    |--------------------------------------------------------------------------
    | API TOKEN TO AUTHENTICATE WITH AQ SERVER. (YOU CAN CREATE ONE FROM PROFILE).
    */
    'api_token' => env('AQ_WHATSAPP_API_TOKEN'),

    /*
    |--------------------------------------------------------------------------
    | SESSION UUID (THIS IS UNIQUE FOR EACH SESSION).
    */
    'session_uuid' => env('AQ_WHATSAPP_SESSION_UUID'),
];
