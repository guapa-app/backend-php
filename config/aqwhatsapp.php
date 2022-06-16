<?php
// config for AQuadic/AQWhatsapp
return [

    /*
    |--------------------------------------------------------------------------
    | API TOKEN TO AUTHENTICATE WITH AQ SERVER. (YOU CAN CREATE ONE FROM PROFILE).
    */
    'api_token' => env('AQ_WHATSAPP_API_TOKEN', "3|CMgNF8eZYdUoKQ2dcb9FtBthImeXEPOZoKEC2sV4"),

    /*
    |--------------------------------------------------------------------------
    | SESSION UUID (THIS IS UNIQUE FOR EACH SESSION).
    */
    'session_uuid' => env('AQ_WHATSAPP_SESSION_UUID', "96852687-2f24-417b-afca-83d5771aabfc"),
];
