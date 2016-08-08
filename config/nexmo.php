<?php
return [

    /*
    |--------------------------------------------------------------------------
    | API Credentials
    |--------------------------------------------------------------------------
    |
    | If you're using API credentials, change these settings. Get your
    | credentials from https://dashboard.nexmo.com | 'Settings'.
    |
    */

    'api_key'               => env('NEXMO_API_KEY', null),
    'api_secret'            => env('NEXMO_API_SECRET', null),

    /*
    |--------------------------------------------------------------------------
    | Signature Secret
    |--------------------------------------------------------------------------
    |
    | If you're using a signature secret, use this section.
    |
    */

    //'api_key'             => env('NEXMO_API_KEY', null),
    //'signature_secret'    => env('NEXMO_API_SIGNATURE_SECRET', null),

    'from'                  => env('NEXMO_FROM', null),

];
