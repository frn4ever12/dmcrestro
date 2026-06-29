<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Payment Gateway Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for various payment gateways
    |
    */

    'default_gateway' => env('DEFAULT_PAYMENT_GATEWAY', 'cash'),

    'esewa' => [
        'merchant_code' => env('ESEWA_MERCHANT_CODE'),
        'service_charge' => env('ESEWA_SERVICE_CHARGE', 0),
        'test_mode' => env('ESEWA_TEST_MODE', true),
    ],

    'khalti' => [
        'public_key' => env('KHALTI_PUBLIC_KEY'),
        'secret_key' => env('KHALTI_SECRET_KEY'),
        'test_mode' => env('KHALTI_TEST_MODE', true),
    ],

    'fonepay' => [
        'merchant_id' => env('FONEPAY_MERCHANT_ID'),
        'secret_key' => env('FONEPAY_SECRET_KEY'),
        'app_id' => env('FONEPAY_APP_ID'),
        'test_mode' => env('FONEPAY_TEST_MODE', true),
    ],

    'connectips' => [
        'merchant_id' => env('CONNECTIPS_MERCHANT_ID'),
        'secret_key' => env('CONNECTIPS_SECRET_KEY'),
        'app_id' => env('CONNECTIPS_APP_ID'),
        'test_mode' => env('CONNECTIPS_TEST_MODE', true),
    ],

    'stripe' => [
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
        'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
    ],
];
