# Laravel PIX

![Readme banner](docs/banner.png)

> Work In Progress

Este pacote oferece integração completa com a API PIX do banco central do Brasil.

# Instalação
Você pode instalar este pacote utilizando o composer:
```bash
composer require mateusjunges/laravel-pix
```

Agora, é necessário publicar os assets utilizados e o arquivo de configuração do pacote.

## Publicando os assets

Para publicar os assets deste pacote para a pasta public do seu projeto, utilize o comando
```bash
php artisan vendor:publish --tag=laravel-pix-assets
```

## Publicando o arquivo de configuração

Para publicar o arquivo de configuração, execute o comando abaixo:

```bash
php artisan vendor:publish --tag=laravel-pix-config
```

Este comando vai copiar o arquivo `laravel-pix.php` para sua pasta config, com o seguinte conteúdo:

```php
<?php

return [
    'currency_code' => 986,

    'country_code' => 'BR',

    'gui' => 'br.gov.bcb.pix',

    'country_phone_prefix' => '+55',

    'qr_code_size' => 200,

    'create_qr_code_route_middleware' => '',

    'psp' => [
        'psp_base_url' => env('LARAVEL_PIX_PSP_BASE_URL'),
        'oauth_bearer_token' => env('LARAVEL_PIX_OAUTH2_BEARER_TOKEN'),
    ]
];
```
