<?php

return [
    'currency_code' => 986,

    'country_code' => 'BR',

    'gui' => 'br.gov.bcb.pix',

    'country_phone_prefix' => '+55',

    'qr_code_size' => 200,

    'create_qr_code_route_middleware' => '',

    'psp' => [
        'base_url' => env('LARAVEL_PIX_PSP_BASE_URL'),
        'oauth_bearer_token' => env('LARAVEL_PIX_OAUTH2_BEARER_TOKEN'),
        'ssl_certificate' => env('LARAVEL_PIX_PSP_SSL_CERTIFICATE'),
        'client_secret' => env('LARAVEL_PIX_PSP_CLIENT_SECRET'),
        'client_id' => env('LARAVEL_PIX_PSP_CLIENT_ID')
    ]
];