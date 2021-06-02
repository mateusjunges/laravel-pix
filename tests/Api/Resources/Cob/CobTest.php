<?php

namespace Junges\Pix\Tests\Api\Resources\Cob;

use Illuminate\Container\Container;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Junges\Pix\Api\Filters\CobFilters;
use Junges\Pix\Exceptions\ValidationException;
use Junges\Pix\Pix;
use Junges\Pix\Psp;
use Junges\Pix\Tests\TestCase;
use Mockery as m;

class CobTest extends TestCase
{
    private array $response;

    public function setUp(): void
    {
        parent::setUp();
        $this->response = [
            'calendario' => [
                'criacao'   => '2021-05-11T03:07:23.845Z',
                'expiracao' => 36000,
            ],
            'txid'    => 'OLtfsYyFwSLs3uGma6Ty5ZEKjg',
            'revisao' => 0,
            'loc'     => [
                'id'      => 24,
                'location'=> 'pix-h.example.com/v2/265d9f2d3cdc4a1ba0b311ff2812d0dc',
                'tipoCob' => 'cob',
                'criacao' => '2021-05-11T03:07:23.880Z',
            ],
            'location' => 'pix-h.example.com/v2/265d9f2d3cdc4a1ba0b311ff2812d0dc',
            'status'   => 'ATIVA',
            'devedor'  => [
                'cpf' => '54484011042',
                'nome'=> 'Fulano de Tal',
            ],
            'valor'=> [
                'original'=> '8.00',
            ],
            'chave'             => $this->randomKey,
            'solicitacaoPagador'=> 'Pagamento de serviço',
        ];
    }

    public function tearDown(): void
    {
        m::close();
        Container::setInstance(null);
    }

    public function test_it_can_create_a_cob()
    {
        Http::fake([
            'https://pix.example.com/v2/cob/*' => Http::response($this->response, 200),
        ]);

        $request = json_decode(
            '{"calendario":{"expiracao":3600},"devedor":{"cnpj":"12345678000195","nome":"Empresa de Serviços SA"},"valor":{"original":"37.00","modalidadeAlteracao":1},"chave":"7d9f0335-8dcc-4054-9bf9-0dbd61d36906","solicitacaoPagador":"Serviço realizado.","infoAdicionais":[{"nome":"Campo 1","valor":"Informação Adicional1 do PSP-Recebedor"},{"nome":"Campo 2","valor":"Informação Adicional2 do PSP-Recebedor"}]}',
            true
        );

        $transactionId = Str::random();

        $cob = Pix::cob()->create($transactionId, $request);

        $this->assertTrue($cob->successful());
        $this->assertEquals($this->response, $cob->json());
    }

    public function test_i_can_use_cob_switching_psps()
    {
        Http::fake([
            $this->dummyPspUrl => Http::response($this->response, 200),
        ]);

        $request = json_decode(
            '{"calendario":{"expiracao":3600},"devedor":{"cnpj":"12345678000195","nome":"Empresa de Serviços SA"},"valor":{"original":"37.00","modalidadeAlteracao":1},"chave":"7d9f0335-8dcc-4054-9bf9-0dbd61d36906","solicitacaoPagador":"Serviço realizado.","infoAdicionais":[{"nome":"Campo 1","valor":"Informação Adicional1 do PSP-Recebedor"},{"nome":"Campo 2","valor":"Informação Adicional2 do PSP-Recebedor"}]}',
            true
        );

        $transactionId = Str::random();

        $cob = Pix::cob()->usingPsp('dummy-psp')->create($transactionId, $request);

        $this->assertTrue($cob->successful());
        $this->assertEquals($this->response, $cob->json());
        $this->assertEquals('default', Psp::getDefaultPsp());
    }

    public function test_it_returns_to_default_psp_if_i_switch_in_only_one_request()
    {
        Http::fake([
            'https://pix.dummy-psp.com/v2/cob/*' => Http::response($this->response, 200),
        ]);

        $request = json_decode(
            '{"calendario":{"expiracao":3600},"devedor":{"cnpj":"12345678000195","nome":"Empresa de Serviços SA"},"valor":{"original":"37.00","modalidadeAlteracao":1},"chave":"7d9f0335-8dcc-4054-9bf9-0dbd61d36906","solicitacaoPagador":"Serviço realizado.","infoAdicionais":[{"nome":"Campo 1","valor":"Informação Adicional1 do PSP-Recebedor"},{"nome":"Campo 2","valor":"Informação Adicional2 do PSP-Recebedor"}]}',
            true
        );

        $transactionId = Str::random();

        $cob = Pix::cob();

        $response = $cob->usingPsp('dummy-psp')->create($transactionId, $request);

        Http::assertSent(function (Request $request) {
            return Str::contains($request->url(), 'https://pix.dummy-psp.com/v2');
        });

        $this->assertTrue($response->successful());
        $this->assertEquals($this->response, $response->json());
        $this->assertEquals('dummy-psp', $cob->getPsp()->getCurrentPsp());

        Http::fake([
            'https://pix.example.com/v2/*' => Http::response($this->response, 200),
        ]);

        $cob = Pix::cob();

        $response = $cob->create($transactionId, $request);

        $this->assertTrue($response->successful());
        $this->assertEquals($this->response, $response->json());
        $this->assertEquals('default', Psp::getDefaultPsp());
    }

    public function test_it_can_create_a_cob_without_transaction_id()
    {
        Http::fake([
            'https://pix.example.com/v2/cob/*' => $this->response,
        ]);

        $request = json_decode(
            '{"calendario":{"expiracao":3600},"devedor":{"cnpj":"12345678000195","nome":"Empresa de Serviços SA"},"valor":{"original":"37.00","modalidadeAlteracao":1},"chave":"7d9f0335-8dcc-4054-9bf9-0dbd61d36906","solicitacaoPagador":"Serviço realizado.","infoAdicionais":[{"nome":"Campo 1","valor":"Informação Adicional1 do PSP-Recebedor"},{"nome":"Campo 2","valor":"Informação Adicional2 do PSP-Recebedor"}]}',
            true
        );

        $cob = Pix::cob()->createWithoutTransactionId($request);

        $this->assertTrue($cob->successful());
        $this->assertEquals($this->response, $cob->json());
    }

    public function test_it_can_get_a_cob_by_its_transaction_id()
    {
        Http::fake([
            'https://pix.example.com/v2/cob/*' => Http::response($this->response),
        ]);

        $this->assertEquals($this->response, Pix::cob()->getByTransactionId('OLtfsYyFwSLs3uGma6Ty5ZEKjg')->json());
    }

    public function test_it_can_get_all_cobs()
    {
        $response = [
            'parametros' => [
                'inicio'    => '2021-04-11T03:45:34.192Z',
                'fim'       => '2021-06-11T03:45:34.192Z',
                'paginacao' => [
                    'paginaAtual'            => 0,
                    'itensPorPagina'         => 100,
                    'quantidadeDePaginas'    => 0,
                    'quantidadeTotalDeItens' => 0,
                ],
                'cpf'              => '12345678900',
                'locationPresente' => 'false',
            ],
            'cobs' => [],
        ];

        Http::fake([
            'https://pix.example.com/v2/cob/*' => Http::response($response),
        ]);

        $filters = (new CobFilters())
            ->startingAt(now()->subMonth()->toISOString())
            ->endingAt(now()->addMonth()->toISOString());

        $cob = Pix::cob()->withFilters($filters)->all();

        $this->assertEquals($response, $cob->json());
        $this->assertTrue($cob->successful());
    }

    public function test_it_can_update_a_cob()
    {
        Http::fake([
            'https://pix.example.com/v2/cob/*' => Http::response($this->response),
        ]);

        $transactionId = Str::random();

        $request = json_decode(
            '{"calendario":{"expiracao":3600},"devedor":{"cnpj":"12345678000195","nome":"Empresa de Serviços SA"},"valor":{"original":"37.00","modalidadeAlteracao":1},"chave":"7d9f0335-8dcc-4054-9bf9-0dbd61d36906","solicitacaoPagador":"Serviço realizado.","infoAdicionais":[{"nome":"Campo 1","valor":"Informação Adicional1 do PSP-Recebedor"},{"nome":"Campo 2","valor":"Informação Adicional2 do PSP-Recebedor"}]}',
            true
        );

        $cob = Pix::cob()->updateByTransactionId($transactionId, $request);

        $this->assertEquals($this->response, $cob->json());
        $this->assertTrue($cob->successful());
    }

    public function test_it_apply_filters_to_the_query()
    {
        $response = [
            'parametros' => [
                'inicio'    => '2021-04-11T03:45:34.192Z',
                'fim'       => '2021-06-11T03:45:34.192Z',
                'paginacao' => [
                    'paginaAtual'            => 0,
                    'itensPorPagina'         => 100,
                    'quantidadeDePaginas'    => 0,
                    'quantidadeTotalDeItens' => 0,
                ],
                'cpf'              => '12345678900',
                'locationPresente' => 'false',
            ],
            'cobs' => [],
        ];

        Http::fake([
            'https://pix.example.com/v2/cob/*' => Http::response($response),
        ]);

        $start = now()->subMonth()->toISOString();
        $end = now()->addMonth()->toIsoString();

        $filters = (new CobFilters())
            ->startingAt($start)
            ->endingAt($end);

        Pix::cob()->withFilters($filters)->all()->json();

        Http::assertSent(function (Request $request) use ($start, $end) {
            return $request->data() === ['inicio' => $start, 'fim' => $end]
                || Str::contains($request->url(), http_build_query([
                    'inicio' => $start,
                    'fim'    => $end,
                ]));
        });

        $cpf = '19220677091';
        $status = 'ATIVA';

        $filters->cpf($cpf)->withStatus($status);

        Pix::cob()->withFilters($filters)->all()->json();

        Http::assertSent(function (Request $request) use ($start, $end, $status, $cpf) {
            return $request->data() === [
                'inicio' => $start,
                'fim'    => $end,
                'cpf'    => $cpf,
                'status' => $status,
            ]
                || Str::contains($request->url(), http_build_query([
                    'inicio' => $start,
                    'fim'    => $end,
                    'cpf'    => $cpf,
                    'status' => $status,
                ]));
        });
    }

    public function test_it_throws_validation_exception_if_filters_are_not_set()
    {
        $this->expectException(ValidationException::class);

        Pix::cob()->all()->json();
    }
}
