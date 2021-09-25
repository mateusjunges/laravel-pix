<?php

return [

    'transaction_currency_code' => 986,

    'country_code' => 'BR',

    /*
     | O PIX precisa definir seu GUI (Global Unique Identifier) para ser utilizado.
     */
    'gui' => 'br.gov.bcb.pix',

    'country_phone_prefix' => '+55',

    /*
     * Tamanho do QR code quer será gerado pelo gerador implementado no pacote, em pixels.
     */
    'qr_code_size' => 200,

    /*
     * Você pode definir um middleware para proteger a rota disponibilizada para gerar QR codes.
     * O nome registrado para este middleware precisa ser definido aqui.
     */
    'create_qr_code_route_middleware' => '',

    /*
     * Informações do Prestador de serviço de pagamento (PSP) que você está utilizando.
     * Você pode utilizar vários psps com este pacote, bastando adicionar um novo array com configurações.
     * base_url: URL base da API do seu PSP.
     * oauth_bearer_token: Você pode definir o seu Token
     */
    'psp' => [
        'default' => [
            'base_url'                => env('LARAVEL_PIX_PSP_BASE_URL'),
            'oauth_token_url'         => env('LARAVEL_PIX_PSP_OAUTH_URL', false),
            'oauth_bearer_token'      => env('LARAVEL_PIX_OAUTH2_BEARER_TOKEN'),
            'ssl_certificate'         => env('LARAVEL_PIX_PSP_SSL_CERTIFICATE'),
            'client_secret'           => env('LARAVEL_PIX_PSP_CLIENT_SECRET'),
            'client_id'               => env('LARAVEL_PIX_PSP_CLIENT_ID'),
            'authentication_class'    => \Junges\Pix\Api\Contracts\AuthenticatesPSPs::class,
            'resolve_endpoints_using' => \Junges\Pix\Support\Endpoints::class,
        ],
    ],
];
