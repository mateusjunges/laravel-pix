<?php

namespace Junges\Pix\Tests\Api\Resources\Cobv;

use Illuminate\Container\Container;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Junges\Pix\Api\Filters\CobvFilters;
use Junges\Pix\Exceptions\ValidationException;
use Junges\Pix\Pix;
use Junges\Pix\Psp;
use Junges\Pix\Tests\TestCase;
use Mockery as m;

class CobvTest extends TestCase
{
    private array $response;
    private array $getResponse;

    public function setUp(): void
    {
        parent::setUp();
        $this->response = [
            'calendario' => [
                'criacao'                => '2020-09-09T20:15:00.358Z',
                'dataDeVencimento'       => '2020-12-31',
                'validadeAposVencimento' => 30,
            ],
            'txid'    => '7978c0c97ea847e78e8849634473c1f1',
            'revisao' => 0,
            'loc'     => [
                'id'       => 789,
                'location' => 'pix.example.com/qr/c2/cobv/9d36b84fc70b478fb95c12729b90ca25',
                'tipoCob'  => 'cobv',
            ],
            'status'  => 'ATIVA',
            'devedor' => [
                'logradouro' => 'Rua 15, Numero 1, Bairro Luz',
                'cidade'     => 'Belo Horizonte',
                'uf'         => 'MG',
                'cep'        => '99000750',
                'cnpj'       => '12345678000195',
                'nome'       => 'Empresa de Serviços SA',
            ],
            'recebedor' => [
                'logradouro' => 'Rua 15 Numero 1200, Bairro São Luiz',
                'cidade'     => 'São Paulo',
                'uf'         => 'SP',
                'cep'        => '70800100',
                'cnpj'       => '56989000019533',
                'nome'       => 'Empresa de Logística SA',
            ],
            'valor' => [
                'original' => '567.89',
            ],
            'chave'              => 'a1f4102e-a446-4a57-bcce-6fa48899c1d1',
            'solicitacaoPagador' => 'Informar cartão fidelidade',
        ];
        $this->getResponse = [
            'calendario' => [
                'criacao'                => '2020-09-09T20:15:00.358Z',
                'dataDeVencimento'       => '2020-12-31',
                'validadeAposVencimento' => 30,
            ],
            'txid'    => '7978c0c97ea847e78e8849634473c1f1',
            'revisao' => 0,
            'loc'     => [
                'id'       => 789,
                'location' => 'pix.example.com/qr/c2/cobv/9d36b84fc70b478fb95c12729b90ca25',
                'tipoCob'  => 'cobv',
            ],
            'status'  => 'ATIVA',
            'devedor' => [
                'logradouro' => 'Rua 15, Numero 1, Bairro Luz',
                'cidade'     => 'Belo Horizonte',
                'uf'         => 'MG',
                'cep'        => '99000750',
                'cnpj'       => '12345678000195',
                'nome'       => 'Empresa de Serviços SA',
            ],
            'recebedor' => [
                'logradouro' => 'Rua 15 Numero 1200, Bairro São Luiz',
                'cidade'     => 'São Paulo',
                'uf'         => 'SP',
                'cep'        => '70800100',
                'cnpj'       => '56989000019533',
                'nome'       => 'Empresa de Logística SA',
            ],
            'valor' => [
                'original' => '567.89',
            ],
            'chave'              => 'a1f4102e-a446-4a57-bcce-6fa48899c1d1',
            'solicitacaoPagador' => 'Informar cartão fidelidade',
        ];
    }

    protected function tearDown(): void
    {
        m::close();
        Container::setInstance(null);
    }

    public function test_it_can_create_a_cobv_with_transaction_id()
    {
        Http::fake([
            'https://pix.example.com/v2/cobv/*' => Http::response($this->response),
        ]);

        $transactionId = Str::random(26);
        $request = json_decode(
            '{"calendario":{"dataDeVencimento":"2020-12-31","validadeAposVencimento":30},"loc":{"id":789},"devedor":{"logradouro":"Alameda Souza, Numero 80, Bairro Braz","cidade":"Recife","uf":"PE","cep":"70011750","cpf":"12345678909","nome":"Francisco da Silva"},"valor":{"original":"123.45","multa":{"modalidade":"2","valorPerc":"15.00"},"juros":{"modalidade":"2","valorPerc":"2.00"},"desconto":{"modalidade":"1","descontoDataFixa":[{"data":"2020-11-30","valorPerc":"30.00"}]}},"chave":"5f84a4c5-c5cb-4599-9f13-7eb4d419dacc","solicitacaoPagador":"Cobrança dos serviços prestados."}',
            true
        );

        $response = Pix::cobv()->createWithTransactionId($transactionId, $request);

        $this->assertEquals($this->response, $response->json());
        $this->assertTrue($response->successful());
    }

    public function test_it_can_create_a_cobv_using_non_default_psp()
    {
        Http::fake([
            $this->dummyPspUrl => Http::response($this->response),
        ]);

        $transactionId = Str::random(26);
        $request = json_decode(
            '{"calendario":{"dataDeVencimento":"2020-12-31","validadeAposVencimento":30},"loc":{"id":789},"devedor":{"logradouro":"Alameda Souza, Numero 80, Bairro Braz","cidade":"Recife","uf":"PE","cep":"70011750","cpf":"12345678909","nome":"Francisco da Silva"},"valor":{"original":"123.45","multa":{"modalidade":"2","valorPerc":"15.00"},"juros":{"modalidade":"2","valorPerc":"2.00"},"desconto":{"modalidade":"1","descontoDataFixa":[{"data":"2020-11-30","valorPerc":"30.00"}]}},"chave":"5f84a4c5-c5cb-4599-9f13-7eb4d419dacc","solicitacaoPagador":"Cobrança dos serviços prestados."}',
            true
        );

        $response = Pix::cobv()->usingPsp('dummy-psp')->createWithTransactionId($transactionId, $request);

        Http::assertSent(function (Request $request) {
            return Str::contains($request->url(), 'https://pix.dummy-psp.com/v2');
        });

        $this->assertEquals($this->response, $response->json());
        $this->assertTrue($response->successful());
        $this->assertEquals('default', Psp::getDefaultPsp());
    }

    public function test_it_can_get_a_cobv_by_its_transaction_id()
    {
        Http::fake([
            'https://pix.example.com/v2/cobv/*' => Http::response($this->getResponse),
        ]);

        $transactionId = Str::random(26);

        $response = Pix::cobv()->getByTransactionId($transactionId);

        $this->assertTrue($response->successful());
        $this->assertEquals($this->getResponse, $response->json());
    }

    public function test_it_can_apply_filters_to_the_query()
    {
        Http::fake([
            'https://pix.example.com/v2/cobv/*' => Http::response($this->getResponse),
        ]);

        $transactionId = Str::random(26);

        $cpf = '60197199011';
        $start = now()->subMonth()->toISOString();
        $end = now()->addMonth()->toISOString();

        $filters = (new CobvFilters())->cpf($cpf)->startingAt($start)->endingAt($end);

        $response = Pix::cobv()->withFilters($filters)->getByTransactionId($transactionId);

        Http::assertSent(function (Request $request) use ($cpf, $start, $end) {
            return $request->data() === [
                'inicio' => $start,
                'fim'    => $end,
                'cpf'    => $cpf,
            ] ||
            Str::contains($request->url(), http_build_query([
                'inicio' => $start,
                'fim'    => $end,
                'cpf'    => $cpf,
            ]));
        });

        $this->assertTrue($response->successful());
        $this->assertEquals($this->getResponse, $response->json());
    }

    public function test_it_throws_validation_exception_if_filters_are_not_set()
    {
        $this->expectException(ValidationException::class);

        Pix::cobv()->all()->json();
    }
}
