<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'link_status_monitor' => [
        'timeout' => env('LINK_STATUS_TIMEOUT', 10),
        'connect_timeout' => env('LINK_STATUS_CONNECT_TIMEOUT', 5),
        'retries' => env('LINK_STATUS_RETRIES', 1),
        'verify_ssl' => env('LINK_STATUS_VERIFY_SSL', true),
        'user_agent' => env('LINK_STATUS_USER_AGENT', 'POLTREE Link Monitor/1.0'),
    ],

];
