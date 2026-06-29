<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Nepali Date Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration options for Nepali Date (Bikram Sambat) system
    |
    */

    'default_date_system' => env('DEFAULT_DATE_SYSTEM', 'both'),
    // Options: 'ad', 'bs', 'both'

    'timezone' => env('NEPALI_TIMEZONE', 'Asia/Kathmandu'),

    'fiscal_year' => [
        'start_month' => 4, // Shrawan (4th month in BS)
        'start_day' => 16,
    ],

    'display' => [
        'show_both_dates' => true,
        'nepali_first' => true,
        'separator' => ' / ',
    ],

    'locale' => env('APP_LOCALE', 'en'),
    // Options: 'en', 'ne'

    'use_nepali_digits' => env('USE_NEPALI_DIGITS', false),
];
