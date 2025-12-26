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

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN', 'deverkeersborden.nl'),
        'secret' => env('MAILGUN_SECRET', 'key-e75a7bd57849e1094d7c67301096582a'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'mandrill' => [
        'secret' => env('MANDRILL_SECRET', 'K9tHENiNJHorWj3kwd47Bw'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'stripe' => [
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
        'webhook' => env('STRIPE_WEBHOOK_SECRET'),
    ],

    'facebook' => [
        'client_id' => env('FACEBOOK_CLIENT_ID', '903671289765795'),
        'client_secret' => env('FACEBOOK_CLIENT_SECRET', '981609bc873cacb891f78457f27c0a3f'),
        'redirect' => env('FACEBOOK_REDIRECT_URI', 'http://deverkeersborden.nl/auth/facebook'),
    ],

    'twitter' => [
        'client_id' => env('TWITTER_CLIENT_ID', 'fe0dJWM0VKFgF41CkQLDnnSj9'),
        'client_secret' => env('TWITTER_CLIENT_SECRET', 'RhuTUe2u8B9LEx62hy6nJgrwlsezPUtYMNtNOhmenmp2t8Hq1r'),
        'redirect' => env('TWITTER_REDIRECT_URI', 'http://deverkeersborden.nl/auth/twitter'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

];
