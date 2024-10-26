<?php

// config for Jorjika/BogPayment
return [
    'callback_url' => env('BOG_CALLBACK_URL', ''),
    'redirect_urls' => [
        'success' => env('BOG_REDIRECT_SUCCESS'),
        'fail' => env('BOG_REDIRECT_FAIL')
    ],
    'client_id' => env('BOG_CLIENT_ID', ''),
    'secret' => env('BOG_SECRET', ''),
    'base_url' => env('BOG_BASE_URL', 'https://api.bog.ge/payments/v1')
];
