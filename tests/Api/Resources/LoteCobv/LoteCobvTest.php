<?php

namespace Junges\Pix\Tests\Api\Resources\LoteCobv;

use Illuminate\Container\Container;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Junges\Pix\Pix;
use Junges\Pix\Tests\TestCase;
use Mockery as m;

class LoteCobvTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    protected function tearDown(): void
    {
        m::close();
        Container::setInstance(null);
    }

    public function test_it_can_create_batch_cobvs()
    {
        Http::fake([
            'pix.example.com/v2/*' => Http::response([], 200)
        ]);

        $id = Str::random(8);

        $request = json_decode('{"descricao":"Cobranças dos alunos do turno vespertino","cobsv":[{"calendario":{"dataDeVencimento":"2020-12-31","validadeAposVencimento":30},"txid":"fb2761260e554ad593c7226beb5cb650","loc":{"id":789},"devedor":{"logradouro":"Alameda Souza, Numero 80, Bairro Braz","cidade":"Recife","uf":"PE","cep":"70011750","cpf":"08577095428","nome":"João Souza"},"valor":{"original":"100.00"},"chave":"7c084cd4-54af-4172-a516-a7d1a12b75cc","solicitacaoPagador":"Informar matrícula"},{"calendario":{"dataDeVencimento":"2020-12-31","validadeAposVencimento":30},"txid":"7978c0c97ea847e78e8849634473c1f1","loc":{"id":57221},"devedor":{"logradouro":"Rua 15, Numero 1, Bairro Campo Grande","cidade":"Recife","uf":"PE","cep":"70055751","cpf":"15311295449","nome":"Manoel Silva"},"valor":{"original":"100.00"},"chave":"7c084cd4-54af-4172-a516-a7d1a12b75cc","solicitacaoPagador":"Informar matrícula"}]}',
            true
        );

        $response = Pix::loteCobv()->createBatch($id, $request);

        $this->assertEquals(200, $response->status());
    }
}