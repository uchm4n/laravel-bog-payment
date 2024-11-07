<?php

/*
|--------------------------------------------------------------------------
| BOG Payment Configuration
|--------------------------------------------------------------------------
|
| This file is for setting up the Bank of Georgia payment gateway integration.
| You can define your callback URLs, API credentials, and other necessary
| settings here. Make sure to update these values in your environment file.
|
*/

return [
    /*
    |--------------------------------------------------------------------------
    | Callback URL
    |--------------------------------------------------------------------------
    |
    | This URL is used by BOG to send payment notifications to your application.
    | Make sure this endpoint is accessible publicly and handles the callback
    | appropriately to update your payment records.
    |
    */
    'callback_url' => env('BOG_CALLBACK_URL'),

    /*
    |--------------------------------------------------------------------------
    | Redirect URLs
    |--------------------------------------------------------------------------
    |
    | After the payment process, users will be redirected to these URLs depending
    | on whether the payment was successful or failed. Set these URLs to ensure
    | a smooth user experience.
    |
    */
    'redirect_urls' => [
        /*
        | URL to redirect to on successful payment
        */
        'success' => env('BOG_REDIRECT_SUCCESS'),

        /*
        | URL to redirect to on failed payment
        */
        'fail' => env('BOG_REDIRECT_FAIL'),
    ],

    /*
    |--------------------------------------------------------------------------
    | BOG API Credentials
    |--------------------------------------------------------------------------
    |
    | These credentials are used to authenticate your application with the
    | Bank of Georgia payment API. Make sure to keep these values secure.
    |
    */
    'client_id' => env('BOG_CLIENT_ID', ''),
    'secret' => env('BOG_SECRET', ''),

    /*
    |--------------------------------------------------------------------------
    | BOG Payment API Base URL
    |--------------------------------------------------------------------------
    |
    | The base URL for accessing the Bank of Georgia payment API. You can set
    | this to the test or live endpoint depending on your environment.
    |
    */
    'base_url' => env('BOG_BASE_URL', 'https://api.bog.ge/payments/v1'),

    /*
    |--------------------------------------------------------------------------
    | BOG Public Key
    |--------------------------------------------------------------------------
    |
    | This public key is used to verify the signature of the callback requests
    | sent by the Bank of Georgia payment gateway. Make sure to keep this key
    | up to date in your environment file.
    | Here you can see the latest public key: https://api.bog.ge/docs/payments/standard-process/callback
    |
    */
    'public_key' => env('BOG_PUBLIC_KEY'),
];
