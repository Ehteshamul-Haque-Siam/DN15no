<?php

return [
    'postmark' => ['token' => env('POSTMARK_TOKEN')],

    'ses' => [
        'key'    => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'bkash' => [
        'merchant_number' => env('BKASH_MERCHANT_NUMBER', '01761983617'),
        'webhook_secret'  => env('BKASH_WEBHOOK_SECRET'),
    ],

    'sms' => [
        'enabled'  => env('SMS_ENABLED', false),
        'gateway'  => env('SMS_GATEWAY', 'routemobile'),
        'server'   => env('SMS_SERVER', 'apibd.rmlconnect.net'),
        'port'     => env('SMS_PORT', 80),
        'username' => env('SMS_USERNAME'),
        'password' => env('SMS_PASSWORD'),
        'sender'   => env('SMS_SENDER'),
        'endpoint' => env('SMS_ENDPOINT'),
        'key'      => env('SMS_API_KEY'),
    ],

    'admin' => [
        'allowed_ips' => env('ADMIN_ALLOWED_IPS', ''),
    ],
];