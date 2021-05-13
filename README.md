# Laravel PIX

![Readme banner](docs/banner.png)

> Work In Progress

- [Instalação](#instalao)
  - [Publicando os assets](#publicando-os-assets)
  - [Publicando o arquivo de configuração](#publicando-o-arquivo-de-configurao)
- [Endpoints](#endpoints)
- [Configurações iniciais](#configuraes-iniciais)
  - [Obtendo o token de acesso](#obtendo-o-token-de-acesso)
- [Cob](#cob)
  - [Criando uma cobrança imediata](#criando-um-cob)
  - [Revisando uma cobrança imediata](#revisar-uma-cobrana-imediata)
  - [Consultando uma cobrança imediata](#consultando-uma-cobrana-imediata)
  - [Criar cobranças imediatas sem `transactionId`](#criando-cobranas-imediatas-sem-transactionid)
  - [Consultando lista de cobranças imediatas](#consultando-lista-de-cobranas-imediatas)

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
     * base_url: URL base da API do seu PSP.
     * oauth_bearer_token: Você pode definir o seu Token
     */
    'psp' => [
        'base_url' => env('LARAVEL_PIX_PSP_BASE_URL'),
        'oauth_token_url' => env('LARAVEL_PIX_PSP_OAUTH_URL', false),
        'oauth_bearer_token' => env('LARAVEL_PIX_OAUTH2_BEARER_TOKEN'),
        'ssl_certificate' => env('LARAVEL_PIX_PSP_SSL_CERTIFICATE'),
        'client_secret' => env('LARAVEL_PIX_PSP_CLIENT_SECRET'),
        'client_id' => env('LARAVEL_PIX_PSP_CLIENT_ID'),
    ]
];
```

# Endpoints
Os endpoints disponibilizados por este pacote são os mesmos implementados pelo Banco Central, e [documentados aqui][doc_bacen].
Entretanto, o seu provedor de serviços de pagamento (PSP) pode não implementar todos eles.

A lista de endpoints completa está descrita aqui:

- Cob (Reúne endpoints destinados a lidar com gerenciamento de cobranças imediatas)
    - `PUT` `/cob/{txid}`: Cria uma cobrança imediata.
    - `PATCH` `/cob/{txid}`: Revisar uma cobrança imediata.
    - `GET` `/cob/{txid}`: Consultar uma cobrança imediata.
    - `POST` `/cob`: Cria uma cobrança imediata com id de transação definido pelo PSP.
    - `GET` `/cob`: Consultar lista de cobranças imediatas.

- CobV (Reúne endpoints destinados a lidar com gerenciamento de cobranças com vencimento.)
    - `PUT` `/cobv/{txid}`: Cria uma cobrança com vencimento.
    - `PATCH` `/cobv/{txid}`: Revisar uma cobrança com vencimento.
    - `GET` `/cobv/{txid}`: Consultar uma cobrança com vencimento.
    - `GET` `/cobv`: Consultar lista de cobranças com vencimento.

- LoteCobV (Reúne endpoints destinados a lidar com gerenciamento de cobranças com vencimento em lote.)
    - `PUT` `/lotecobv/{id}`: Criar/Alterar lote de cobranças com vencimento.
    - `PATCH` `/lotecobv/{id}`: Utilizado para revisar cobranças específicas dentro de um lote de cobranças com vencimento.
    - `GET` `/lotecobv/{id}`: Utilizado para consultar um lote específico de cobranças com vencimento.
    - `GET` `/lotecobv`: Consultar lotes de cobranças com vencimento.

- PayloadLocation (Reúne endpoints destinados a lidar com configuração e remoção de locations para uso dos payloads)
    - `POST` `/loc`: Criar location do payload.
    - `GET` `/loc`: Consultar locations cadastradas.
    - `GET` `/loc/{id}`: Recuperar location do payload.
    - `DELETE` `/loc/{id}/{txid}`: Desvincular uma cobrança de uma location.

- Pix (Reúne endpoints destinados a lidar com gerenciamento de Pix recebidos.)
    - `GET` `/pix/{e2eid}`: Consultar Pix.
    - `GET` `/pix`: Consultar pix recebidos.
    - `PUT` `/pix/{e2eid}/devolucao/{id}`: Solicitar devolucão.
    - `GET` `/pix/{e2eid}/devolucao/{id}`: Consultar devolução.

- Webhook (Reúne endpoints para gerenciamento de notificações por parte do PSP recebedor ao usuário recebedor.)
    - `PUT` `/webhook/{chave}`: Configurar o webhook pix.
    - `GET` `/webhook/{chave}`: Exibir informações acerca do webhook pix.
    - `DELETE` `/webhook/{chave}`: Cancelar o webhook pix.
    - `GET` `/webhook`: Consultar webhooks cadastrados.

# Configurações iniciais.
Para iniciar a utilização da API Pix, você precisa autenticar com o seu PSP via OAuth.
Para isso, é necessário informar o seu `client_id` e `client_secret`, disponibilizados pelo seu PSP.
Isso deve ser feito no arquivo `.env` de sua aplicação:

```text
LARAVEL_PIX_PSP_CLIENT_SECRET="seu client_secret aqui"
LARAVEL_PIX_PSP_CLIENT_ID="seu client_id aqui"
```

Vários PSP que disponibilizam a API Pix possuem uma URL para obtenção do token de acesso diferente da URL para a API. Portanto, você precisa
configurar as duas URLs no seu `.env`:

```text
LARAVEL_PIX_PSP_OAUTH_URL="url para obtenção do access token"
LARAVEL_PIX_PSP_BASE_URL="url da api pix"
```

Agora, todas as chamadas a API Pix utilizarão estas credencias, e você não precisa informar manualmente para cada requisição.
Entretando, se por algum motivo você desejar alterar estas credenciais em tempo de execução, 
é possível através dos métodos `->clientId()` e `->clientSecret()`, disponibilizados em todos os endpoints neste pacote. Um exemplo é mostrado abaixo:

```php
use Junges\Pix\Pix;

$api = Pix::api()
    ->clientId('client_id')
    ->clientSecret('client_secret');
```
Estes métodos estão disponíveis em todos os recursos da api Pix: `cob`, `cobv`, `loteCobv`, `payloadLocation`, `receivedPix` e `webhook`.

## Obtendo o token de acesso
Este pacote disponibiliza uma implementação de autenticação geral, que pode ser utilizada da seguinte forma:
```php
use Junges\Pix\Pix;

// Se você já informou o seu client_id e client_secret no .env, não é necessário informar nesta requisição.
$token = Pix::api()->getOauth2Token()->json();
```
Alguns PSPs requerem a verificação de um certificado disponibilizado no momento da criação de sua aplicação. Este certificado pode ser informado
no `.env`, ou informado na requisição através do método `certificate()`, e será carregado automaticamente na api. 

```php
use Junges\Pix\Pix;

// Se você já informou o seu client_id e client_secret no .env, não é necessário informar nesta requisição.
$token = Pix::api()->certificate('path/to/certificate')->getOauth2Token()->json();
```

Caso os endpoints do PSP utilizado necessitem da verificação deste certificado, você precisa informar 
este pacote para fazer esta verificação. Isto pode ser feito através do `AppServiceProvider` da sua aplicação, bastando adicionar esta linha ao método
`register`: 

```php
use Junges\Pix\LaravelPix;

public function register()
{
    LaravelPix::validatingSslCertificate();
}
```
Agora, todas as chamadas aos endpoints da API Pix farão a verificação com o certificado informado.


implementada por alguns PSPs, mas que não serve para todos.
Por isso, você pode criar a sua própria class de autenticação

# Cob
O Cob reúne os endpoints relacionados a criação de cobranças instantâneas.

> Consulte a documentação oficial do banco central para informações sobre o request a ser enviado para cada endpoint, 
> disponível [neste link](https://bacen.github.io/pix-api/index.html#/Cob/put_cob__txid_).

## Criando um cob
Para criar uma cobrança instantânea, é necessário utilizar a api `cob`, disponibilizada pela classe `Pix`, neste pacote.

```php
use Junges\Pix\Pix;

$cob = Pix::cob()->create('transactionId', $request)->json();
```

## Revisar uma cobrança imediata
Para revisar uma cobrança imediata, deve ser utilizado o método `updateByTransactionId()`, informando o id da transação a ser atualizada e 
os dados para atualização.

```php
use Junges\Pix\Pix;

$updateCob = Pix::cob()->updateByTransactionId('transactionId', $dataToUpdate)->json();
```

## Consultando uma cobrança imediata
Para consultar uma cobrança através de um determinado id de transação, você deve utilizar o método `getByTransactionId`, informando o id 
da transação como parâmetro:

```php
use Junges\Pix\Pix;

$cob = Pix::cob()->getByTransactionId('transactionId')->json();
```
## Criando cobranças imediatas sem `transactionId`
Para criar uma cobrança imediata com `transactionId` definido pelo PSP, utilize o método `createWithoutTransactionId()`,
informando apenas os dados para criação da cobrança, sem a necessidade de passar um id de transação:

```php
use Junges\Pix\Pix;

$cob = Pix::cob()->createWithoutTransactionId($request);
```

## Consultando lista de cobranças imediatas
Para consultar a lista de cobranças imediatas com parâmetros como inicio, fim, status e outros, utilize o método `all()`, 
passando os filtros necessários. Os filtros `inicio` e `fim` são obrigatórios para toda requisição neste endpoint. Este pacote
disponibiliza uma api para aplicação de filtros na requisição, bastando instanciar uma nova classe para os filtros desejados e aplicá-los
a requisição com o método `withFilters()`:

```php
use Junges\Pix\Pix;
use Junges\Pix\Api\Filters\CobFilters;

$filters = (new CobFilters())
    ->startingAt(now()->subMonth()->toISOString())
    ->endingAt(now()->addMonth()->toISOString());

$cobs = Pix::cob()->withFilters($filters)->all()->json();
```

A lista de filtros disponíveis para o endpoint `cob` é listada aqui:

---
Filtro | Método utilizado
--- | ---
inicio | `startingAt()`
fim | `endingAt()`
cpf | `cpf()`
cnpj | `cnpj()`
cnpj | `cnpj()`
locationPresente | `withLocationPresent()` ou `withoutLocationPresent()`
status | `withStatus()`
paginacao.paginaAtual | `currentPage()`
paginacao.itensPorPagina | `itemsPerPage()`
---





[doc_bacen]: https://bacen.github.io/pix-api/index.html#/

