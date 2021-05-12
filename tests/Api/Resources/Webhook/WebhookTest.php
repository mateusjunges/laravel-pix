<?php

namespace Junges\Pix\Tests\Api\Resources\Webhook;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Junges\Pix\Api\Filters\CobFilters;
use Junges\Pix\Api\Filters\WebhookFilters;
use Junges\Pix\Pix;
use Junges\Pix\Tests\TestCase;

class WebhookTest extends TestCase
{
    public function test_it_can_create_a_webhook()
    {
        Http::fake([
            'pix.example.com/v2/*' => Http::response([], 200)
        ]);

        $url = 'pix.example.com/webhook';
        $key = $this->randomKey;

        $webhook = Pix::webhook()->create($key, $url);

        $this->assertTrue($webhook->successful());
    }

    public function test_it_can_delete_a_webhook()
    {
        Http::fake([
            'pix.example.com/v2/*' => Http::response([], 200)
        ]);

        $webhook = Pix::webhook()->delete($this->randomKey);

        $this->assertTrue($webhook->successful());
    }

    public function test_it_can_get_webhooks_by_pix_keys()
    {
        Http::fake([
            'pix.example.com/v2/*' => Http::response($response = [
                'webhookUrl' => 'https://pix.example.com/api/webhook/',
                'chave' => $this->randomKey,
                'criacao' => '2020-11-11T10:15:00.358Z',
            ], 200)
        ]);

        $webhook = Pix::webhook()->getByPixKey($this->randomKey);

        $this->assertEquals($response, $webhook->json());
        $this->assertTrue($webhook->successful());
    }

    public function test_it_can_get_all_webhooks()
    {
        Http::fake([
            'pix.example.com/v2/*' => Http::response($response = [
                'parametros' => [
                    'inicio' => '2020-04-01T00:00:00Z',
                    'fim' => '2020-04-01T23:59:59Z',
                    'paginacao' => [
                        'paginaAtual' => 0,
                        'itensPorPagina' => 100,
                        'quantidadeDePaginas' => 1,
                        'quantidadeTotalDeItens' => 1,
                    ],
                ],
                'webhooks' => [
                    0 => [
                        '$ref' => 'openapi.yaml#/components/examples/webhookResponse1/value',
                    ],
                ],
            ])
        ]);

        $webhooks = Pix::webhook()->all();

        $this->assertTrue($webhooks->successful());
        $this->assertEquals($response, $webhooks->json());
    }


    public function test_it_apply_filters_to_the_query()
    {

        Http::fake([
            'https://pix.example.com/v2/*' => Http::response($response = [
                'parametros' => [
                    'inicio' => '2020-04-01T00:00:00Z',
                    'fim' => '2020-04-01T23:59:59Z',
                    'paginacao' => [
                        'paginaAtual' => 0,
                        'itensPorPagina' => 100,
                        'quantidadeDePaginas' => 1,
                        'quantidadeTotalDeItens' => 1,
                    ],
                ],
                'webhooks' => [
                    0 => [
                        '$ref' => 'openapi.yaml#/components/examples/webhookResponse1/value',
                    ],
                ],
            ])
        ]);

        $start = now()->subMonth()->toISOString();
        $end = now()->addMonth()->toIsoString();

        $filters = (new WebhookFilters())
            ->startingAt($start)
            ->endingAt($end);

        Pix::webhook()->withFilters($filters)->all()->json();

        Http::assertSent(function(Request $request) use ($start, $end) {
            return $request->data() === ['inicio' => $start, 'fim' => $end]
                || Str::contains($request->url(), http_build_query([
                    'inicio' => $start,
                    'fim' => $end,
                ]));
        });

        $currentPage = '1';
        $itemsPerPage = '2';

        $filters->currentPage($currentPage)->itemsPerPage($itemsPerPage);

        Pix::cob()->withFilters($filters)->all()->json();

        Http::assertSent(function(Request $request) use ($start, $end, $itemsPerPage, $currentPage) {
            return $request->data() === [
                    'inicio' => $start,
                    'fim' => $end,
                    'paginacao.paginaAtual' => $currentPage,
                    'paginacao.itensPorPagina' => $itemsPerPage
                ]
                || Str::contains($request->url(), http_build_query([
                    'inicio' => $start,
                    'fim' => $end,
                    'paginacao.paginaAtual' => $currentPage,
                    'paginacao.itensPorPagina' => $itemsPerPage
                ]));
        });
    }

}