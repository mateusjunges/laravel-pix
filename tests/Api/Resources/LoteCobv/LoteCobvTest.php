<?php

namespace Junges\Pix\Tests\Api\Resources\LoteCobv;

use Illuminate\Container\Container;
use Illuminate\Http\Client\Request;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Junges\Pix\Api\Filters\LoteCobvFilter;
use Junges\Pix\Exceptions\ValidationException;
use Junges\Pix\Pix;
use Junges\Pix\Psp;
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
            'pix.example.com/v2/*' => Http::response([], 200),
        ]);

        $id = Str::random(8);

        $request = json_decode(
            '{"descricao":"Cobranças dos alunos do turno vespertino","cobsv":[{"calendario":{"dataDeVencimento":"2020-12-31","validadeAposVencimento":30},"txid":"fb2761260e554ad593c7226beb5cb650","loc":{"id":789},"devedor":{"logradouro":"Alameda Souza, Numero 80, Bairro Braz","cidade":"Recife","uf":"PE","cep":"70011750","cpf":"08577095428","nome":"João Souza"},"valor":{"original":"100.00"},"chave":"7c084cd4-54af-4172-a516-a7d1a12b75cc","solicitacaoPagador":"Informar matrícula"},{"calendario":{"dataDeVencimento":"2020-12-31","validadeAposVencimento":30},"txid":"7978c0c97ea847e78e8849634473c1f1","loc":{"id":57221},"devedor":{"logradouro":"Rua 15, Numero 1, Bairro Campo Grande","cidade":"Recife","uf":"PE","cep":"70055751","cpf":"15311295449","nome":"Manoel Silva"},"valor":{"original":"100.00"},"chave":"7c084cd4-54af-4172-a516-a7d1a12b75cc","solicitacaoPagador":"Informar matrícula"}]}',
            true
        );

        $response = Pix::loteCobv()->createBatch($id, $request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertTrue($response->successful());
    }

    public function test_it_can_create_batch_cobvs_using_non_default_psp()
    {
        Http::fake([
            $this->dummyPspUrl => Http::response([], 200),
        ]);

        $id = Str::random(8);

        $request = json_decode(
            '{"descricao":"Cobranças dos alunos do turno vespertino","cobsv":[{"calendario":{"dataDeVencimento":"2020-12-31","validadeAposVencimento":30},"txid":"fb2761260e554ad593c7226beb5cb650","loc":{"id":789},"devedor":{"logradouro":"Alameda Souza, Numero 80, Bairro Braz","cidade":"Recife","uf":"PE","cep":"70011750","cpf":"08577095428","nome":"João Souza"},"valor":{"original":"100.00"},"chave":"7c084cd4-54af-4172-a516-a7d1a12b75cc","solicitacaoPagador":"Informar matrícula"},{"calendario":{"dataDeVencimento":"2020-12-31","validadeAposVencimento":30},"txid":"7978c0c97ea847e78e8849634473c1f1","loc":{"id":57221},"devedor":{"logradouro":"Rua 15, Numero 1, Bairro Campo Grande","cidade":"Recife","uf":"PE","cep":"70055751","cpf":"15311295449","nome":"Manoel Silva"},"valor":{"original":"100.00"},"chave":"7c084cd4-54af-4172-a516-a7d1a12b75cc","solicitacaoPagador":"Informar matrícula"}]}',
            true
        );

        $response = Pix::loteCobv()->usingPsp('dummy-psp')->createBatch($id, $request);

        Http::assertSent(function (Request $request) {
            return Str::contains($request->url(), 'https://pix.dummy-psp.com/v2');
        });

        $this->assertInstanceOf(Response::class, $response);
        $this->assertTrue($response->successful());
        $this->assertEquals('default', Psp::getDefaultPsp());
    }

    public function test_it_can_get_a_cobv_batch()
    {
        $json_response = json_decode(
            '{"descricao":"Cobranças dos alunos do turno vespertino","criacao":"2020-11-01T20:15:00.358Z","cobsv":[{"criacao":"2020-11-01T20:15:00.358Z","txid":"fb2761260e554ad593c7226beb5cb650","status":"CRIADA"},{"txid":"7978c0c97ea847e78e8849634473c1f1","status":"NEGADA","problema":{"type":"https://pix.bcb.gov.br/api/v2/error/CobVOperacaoInvalida","title":"Cobrança inválida.","status":400,"detail":"A requisição que busca alterar ou criar uma cobrança com vencimento não respeita o schema ou está semanticamente errada.","violacoes":[{"razao":"O objeto cobv.devedor não respeita o schema.","propriedade":"cobv.devedor"}]}}]}',
            true
        );

        Http::fake([
            'pix.example.com/v2/*' => Http::response($json_response, 200),
        ]);

        $id = Str::random(8);

        $response = Pix::loteCobv()->getByBatchId($id);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals($json_response, $response->json());
        $this->assertTrue($response->successful());
    }

    public function test_it_can_get_all_batches()
    {
        $json_response = json_decode(
            '{"parametros":{"inicio":"2020-01-01T00:00:00Z","fim":"2020-12-01T23:59:59Z","paginacao":{"paginaAtual":0,"itensPorPagina":100,"quantidadeDePaginas":1,"quantidadeTotalDeItens":2}},"lotes":[{"$ref":"openapi.yaml#/components/examples/loteCobVResponse1/value"},{"$ref":"openapi.yaml#/components/examples/loteCobVResponse2/value"}]}',
            true
        );

        Http::fake([
            'pix.example.com/v2/*' => Http::response($json_response, 200),
        ]);

        $filters = (new LoteCobvFilter())
            ->startingAt(now()->subMonth()->toISOString())
            ->endingAt(now()->addMonth()->toISOString());

        $response = Pix::loteCobv()->withFilters($filters)->all();

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals($json_response, $response->json());
        $this->assertTrue($response->successful());
    }

    public function test_it_can_apply_filters()
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
            ],
            'lotes' => [],
        ];

        Http::fake([
            'https://pix.example.com/v2/cob/*' => Http::response($response),
        ]);

        $start = now()->subMonth()->toISOString();
        $end = now()->addMonth()->toIsoString();

        $filters = (new LoteCobvFilter())
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
    }

    public function test_it_throws_validation_exception_if_filters_are_not_set()
    {
        $this->expectException(ValidationException::class);

        Pix::loteCobv()->all()->json();
    }
}
