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
        'token' => env('POSTMARK_TOKEN'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
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

    'openai' => [
        'key' => env('OPENAI_API_KEY'),
        'model' => env('OPENAI_MODEL', 'gpt-4.1-mini'),
        'ai_import_timeout' => (int) env('OPENAI_AI_IMPORT_TIMEOUT', 45),
        'ai_import_max_tokens' => (int) env('OPENAI_AI_IMPORT_MAX_TOKENS', 8000),
    ],

    'mollie' => [
        'key' => env('MOLLIE_API_KEY'),
        'webhook_url' => env('MOLLIE_WEBHOOK_URL'),
    ],

    'webpush' => [
        'vapid' => [
            'subject' => env('VAPID_SUBJECT'),
            'public_key' => env('VAPID_PUBLIC_KEY'),
            'private_key' => env('VAPID_PRIVATE_KEY'),
        ],
    ],

    'expo' => [
        'enabled' => env('EXPO_PUSH_ENABLED', true),
    ],

    'marketing_link' => [
        'default_destination' => env('MARKETING_LINK_DEFAULT_URL', 'https://taskcheck.nl'),
    ],

    'recaptcha' => [
        'site_key' => env('RECAPTCHA_SITE_KEY'),
        'secret_key' => env('RECAPTCHA_SECRET_KEY'),
        'score_threshold' => (float) env('RECAPTCHA_SCORE_THRESHOLD', 0.5),
    ],

];
