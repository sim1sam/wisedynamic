<?php

return [
    /*
    |--------------------------------------------------------------------------
    | SSL Commerz Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for SSL Commerz payment gateway integration
    |
    */

    'store_id' => env('SSLCOMMERZ_STORE_ID'),
    'store_password' => env('SSLCOMMERZ_STORE_PASSWORD'),
    'sandbox' => env('SSLCOMMERZ_SANDBOX', true),
    
    // Using generic URLs for callbacks to avoid CSRF issues
    'success_url' => env('APP_URL') . '/customer/payment/ssl/success',
    'fail_url' => env('APP_URL') . '/customer/payment/ssl/fail',
    'cancel_url' => env('APP_URL') . '/customer/payment/ssl/cancel',
    'ipn_url' => env('APP_URL') . '/customer/payment/ssl/ipn',
    
    'api_url' => [
        'sandbox' => 'https://sandbox.sslcommerz.com/gwprocess/v4/api.php',
        'live' => 'https://securepay.sslcommerz.com/gwprocess/v4/api.php',
    ],
    
    'validation_url' => [
        'sandbox' => 'https://sandbox.sslcommerz.com/validator/api/validationserverAPI.php',
        'live' => 'https://securepay.sslcommerz.com/validator/api/validationserverAPI.php',
    ],
];