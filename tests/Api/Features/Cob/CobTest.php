<?php

namespace Junges\Pix\Tests\Api\Features\Cob;

use Illuminate\Container\Container;
use Illuminate\Support\Facades\Http;
use Junges\Pix\Api\Features\Cob\CobRequest;
use Junges\Pix\Pix;
use Junges\Pix\Tests\TestCase;
use Mockery as m;

class CobTest extends TestCase
{
    public function tearDown(): void
    {
        m::close();
        Container::setInstance(null);
    }

    public function test_it_can_create_a_cob()
    {
        $response = [
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

        Http::fake([
            'https://pix.example.com/v2/cob/*' => $response
        ]);

        $request = (new CobRequest())
            ->transactionId('OLtfsYyFwSLs3uGma6Ty5ZEKjg')
            ->pixKey($this->randomKey)
            ->payingRequest('Pagamento de serviço')
            ->debtorCpf('54484011042')
            ->debtorName('Fulano de Tal')
            ->amount("8.00");

        $cob = Pix::cob();

        $this->assertEquals($response, $cob->create($request));
    }
}