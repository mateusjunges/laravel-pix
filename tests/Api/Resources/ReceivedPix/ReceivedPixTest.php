<?php

namespace Junges\Pix\Tests\Api\Resources\ReceivedPix;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Junges\Pix\Api\Filters\ReceivedPixFilters;
use Junges\Pix\Events\ReceivedPix\RefundRequestedEvent;
use Junges\Pix\Exceptions\ValidationException;
use Junges\Pix\Pix;
use Junges\Pix\Psp;
use Junges\Pix\Tests\TestCase;

class ReceivedPixTest extends TestCase
{
    public function test_it_can_get_a_pix_by_e2eid()
    {
        Http::fake([
            'pix.example.com/v2/*' => Http::response($response = [
                'endToEndId'  => 'E12345678202009091221abcdef12345',
                'txid'        => 'cd1fe328c875481285a6f233ae41b662',
                'valor'       => '100.00',
                'horario'     => '2020-09-10T13:03:33.902Z',
                'infoPagador' => 'Reforma da casa',
                'devolucoes'  => [
                    0 => [
                        'id'      => '000AAA111',
                        'rtrId'   => 'D12345678202009091000abcde123456',
                        'valor'   => '11.00',
                        'horario' => [
                            'solicitacao' => '2020-09-10T13:03:33.902Z',
                        ],
                        'status' => 'EM_PROCESSAMENTO',
                    ],
                ],
            ]),
        ]);

        $pix = Pix::receivedPix()->getBye2eid('E12345678202009091221abcdef12345');

        $this->assertTrue($pix->successful());
        $this->assertEquals($response, $pix->json());
    }

    public function test_it_can_issue_refunds()
    {
        Http::fake([
            'pix.example.com/v2/*' => Http::response($response = ['valor' => '7.89']),
        ]);

        $pix = Pix::receivedPix()->refund('E12345678202009091221abcdef12345', '123456');

        $this->assertTrue($pix->successful());
        $this->assertEquals($response, $pix->json());
    }

    public function test_it_can_issue_refunds_using_non_default_psp()
    {
        Http::fake([
            $this->dummyPspUrl.'/*' => Http::response($response = ['valor' => '7.89']),
        ]);

        $pix = Pix::receivedPix()->usingPsp('dummy-psp')->refund('E12345678202009091221abcdef12345', '123456');

        Http::assertSent(function (Request $request) {
            return Str::contains($request->url(), 'https://pix.dummy-psp.com/v2');
        });

        Http::assertSent(function (Request $request) {
            return Str::contains($request->url(), 'test-replace-url');
        });

        $this->assertTrue($pix->successful());
        $this->assertEquals($response, $pix->json());
        $this->assertEquals('default', Psp::getDefaultPsp());
    }

    public function test_it_can_consult_a_issued_refund()
    {
        Http::fake([
            'pix.example.com/v2/*' => Http::response($response = [
                'id'      => '123456',
                'rtrId'   => 'D12345678202009091000abcde123456',
                'valor'   => '7.89',
                'horario' => [
                    'solicitacao' => '2020-09-11T15:25:59.411Z',
                ],
                'status' => 'EM_PROCESSAMENTO',
            ]),
        ]);

        $pix = Pix::receivedPix()->consultRefund('E12345678202009091221abcdef12345', '123456');

        $this->assertTrue($pix->successful());
        $this->assertSame($response, $pix->json());
    }

    public function test_it_can_get_all_pix()
    {
        Http::fake([
            'pix.example.com/v2/*' => Http::response($response = [
                'parametros' => [
                    'inicio'    => '2020-04-01T00:00:00Z',
                    'fim'       => '2020-04-01T23:59:59Z',
                    'paginacao' => [
                        'paginaAtual'            => 0,
                        'itensPorPagina'         => 100,
                        'quantidadeDePaginas'    => 1,
                        'quantidadeTotalDeItens' => 2,
                    ],
                ],
                'pix' => [
                    0 => [
                        '$ref' => 'openapi.yaml#/components/examples/pixResponse1/value',
                    ],
                    1 => [
                        '$ref' => 'openapi.yaml#/components/examples/pixResponse2/value',
                    ],
                ],
            ]),
        ]);

        $filters = (new ReceivedPixFilters())
            ->startingAt(now()->subMonth()->toISOString())
            ->endingAt(now()->addMonth()->toISOString());

        $pix = Pix::receivedPix()->withFilters($filters)->all();

        $this->assertTrue($pix->successful());
        $this->assertEquals($response, $pix->json());
    }

    public function test_it_throws_validation_exception_if_filters_are_not_set()
    {
        $this->expectException(ValidationException::class);

        Pix::receivedPix()->all()->json();
    }

    public function test_it_dispatches_refund_requested_event_when_a_refund_is_issued()
    {
        Event::fake();

        Http::fake([
            'pix.example.com/v2/*' => Http::response($response = ['valor' => '7.89']),
        ]);

        $e2eid = 'E12345678202009091221abcdef12345';
        $refundId = '123456';

        $pix = Pix::receivedPix()->refund($e2eid, $refundId);

        Event::assertDispatched(
            RefundRequestedEvent::class,
            function (RefundRequestedEvent $event) use ($e2eid, $refundId) {
                return $event->refund['valor'] === '7.89'
                    && $event->e2eid === $e2eid
                    && $event->refundId === $refundId;
            }
        );

        $this->assertTrue($pix->successful());
        $this->assertEquals($response, $pix->json());
    }
}
