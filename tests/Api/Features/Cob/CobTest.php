<?php

namespace Junges\Pix\Tests\Api\Features\Cob;

use Illuminate\Container\Container;
use Illuminate\Support\Facades\Http;
use Junges\Pix\Api\Features\Cob\CobRequest;
use Junges\Pix\Api\Filters\CobFilters;
use Junges\Pix\Pix;
use Junges\Pix\Tests\TestCase;
use Mockery as m;

class CobTest extends TestCase
{
    private array $response;

    public function setUp(): void
    {
        parent::setUp();
        $this->response = [
            "calendario" => [
                "criacao" => "2021-05-11T03:07:23.845Z",
                "expiracao" => 36000
            ],
            "txid" => "OLtfsYyFwSLs3uGma6Ty5ZEKjg",
            "revisao" => 0,
            "loc"=> [
                "id"=> 24,
                "location"=> "pix-h.example.com/v2/265d9f2d3cdc4a1ba0b311ff2812d0dc",
                "tipoCob"=> "cob",
                "criacao"=> "2021-05-11T03:07:23.880Z"
            ],
            "location" => "pix-h.example.com/v2/265d9f2d3cdc4a1ba0b311ff2812d0dc",
            "status"=> "ATIVA",
            "devedor"=> [
                "cpf"=> "54484011042",
                "nome"=> "Fulano de Tal"
            ],
            "valor"=> [
                "original"=> "8.00"
            ],
            "chave"=> $this->randomKey,
            "solicitacaoPagador"=> "Pagamento de serviço"
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
            'https://pix.example.com/v2/cob/*' => $this->response
        ]);

        $request = (new CobRequest())
            ->transactionId('OLtfsYyFwSLs3uGma6Ty5ZEKjg')
            ->pixKey($this->randomKey)
            ->payingRequest('Pagamento de serviço')
            ->debtorCpf('54484011042')
            ->debtorName('Fulano de Tal')
            ->amount("8.00");

        $cob = Pix::cob();

        $this->assertEquals($this->response, $cob->create($request));
    }

    public function test_it_can_create_a_cob_without_transaction_id()
    {
        Http::fake([
            'https://pix.example.com/v2/cob/*' => $this->response
        ]);

        $request = (new CobRequest())
            ->pixKey($this->randomKey)
            ->payingRequest('Pagamento de serviço')
            ->debtorCpf('54484011042')
            ->debtorName('Fulano de Tal')
            ->amount("8.00");

        $cob = Pix::cob();

        $this->assertEquals($this->response, $cob->createWithoutTransactionId($request));
    }

    public function test_it_can_get_a_cob_by_its_transaction_id()
    {
        Http::fake([
            'https://pix.example.com/v2/cob/*' => $this->response
        ]);

        $this->assertEquals($this->response, Pix::cob()->getByTransactionId("OLtfsYyFwSLs3uGma6Ty5ZEKjg"));
    }

    public function test_it_can_get_all_cobs()
    {
        $response = [
            "parametros" => [
                "inicio" => "2021-04-11T03:45:34.192Z",
                "fim" => "2021-06-11T03:45:34.192Z",
                "paginacao" => [
                    "paginaAtual" => 0,
                    "itensPorPagina" => 100,
                    "quantidadeDePaginas" => 0,
                    "quantidadeTotalDeItens" => 0
                ],
                "cpf" => "10841201943",
                "locationPresente" => "false"
            ],
            "cobs" => []
        ];

        Http::fake([
            'https://pix.example.com/v2/cob/*' => $response
        ]);

        $filters = (new CobFilters())
            ->startingAt(now()->subMonth()->toISOString())
            ->endingAt(now()->addMonth()->toISOString());

        $this->assertEquals($response, Pix::cob()->withFilters($filters)->all());
    }
}