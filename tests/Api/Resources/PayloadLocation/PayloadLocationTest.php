<?php

namespace Junges\Pix\Tests\Api\Resources\PayloadLocation;

use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Junges\Pix\Api\Filters\PayloadLocationFilters;
use Junges\Pix\Exceptions\ValidationException;
use Junges\Pix\Pix;
use Junges\Pix\Psp;
use Junges\Pix\Tests\TestCase;

class PayloadLocationTest extends TestCase
{
    public function test_it_can_create_a_payload_location()
    {
        $response = json_decode(
            '{"id":7716,"location":"pix.example.com/qr/v2/2353c790eefb11eaadc10242ac120002","tipoCob":"cob","criacao":"2020-03-11T21:19:51.013Z"}',
            true
        );

        Http::fake([
            'pix.example.com/v2/*' => Http::response($response),
        ]);

        $payloadLocation = Pix::payloadLocation()->create('cob');

        $this->assertEquals($response, $payloadLocation->json());
        $this->assertTrue($payloadLocation->successful());
    }

    public function test_it_can_create_a_payload_location_using_non_default_psp()
    {
        $response = json_decode(
            '{"id":7716,"location":"pix.example.com/qr/v2/2353c790eefb11eaadc10242ac120002","tipoCob":"cob","criacao":"2020-03-11T21:19:51.013Z"}',
            true
        );

        Http::fake([
            $this->dummyPspUrl => Http::response($response),
        ]);

        $payloadLocation = Pix::payloadLocation()->usingPsp('dummy-psp')->create('cob');

        Http::assertSent(function (Request $request) {
            return Str::contains($request->url(), 'https://pix.dummy-psp.com/v2');
        });

        $this->assertEquals($response, $payloadLocation->json());
        $this->assertTrue($payloadLocation->successful());
        $this->assertEquals('default', Psp::getDefaultPsp());
    }

    public function test_it_can_get_a_location_by_id()
    {
        Http::fake([
            'pix.example.com/v2/*' => Http::response($response = [
                'id'       => 7716,
                'txid'     => 'fda9460fe04e4f129b72863ae57ee22f',
                'location' => 'pix.example.com/qr/v2/cobv/2353c790eefb11eaadc10242ac120002',
                'tipoCob'  => 'cobv',
                'criacao'  => '2020-03-11T21:19:51.013Z',
            ]),
        ]);

        $payload = Pix::payloadLocation()->getById(7716);

        $this->assertTrue($payload->successful());
        $this->assertEquals($response, $payload->json());
    }

    public function test_it_can_get_all_stored_locations()
    {
        $response = json_decode(
            '{"parametros":{"inicio":"2020-04-01T00:00:00Z","fim":"2020-04-01T23:59:59Z","paginacao":{"paginaAtual":0,"itensPorPagina":100,"quantidadeDePaginas":1,"quantidadeTotalDeItens":3}},"loc":[{"$ref":"openapi.yaml#/components/examples/payloadLocationResponse1/value"},{"$ref":"openapi.yaml#/components/examples/payloadLocationResponse2/value"},{"$ref":"openapi.yaml#/components/examples/payloadLocationResponse3/value"}]}',
            true
        );

        Http::fake([
            'pix.example.com/v2/*' => Http::response($response),
        ]);

        $filters = (new PayloadLocationFilters())
            ->startingAt(now()->subMonth()->toISOString())
            ->endingAt(now()->addMonth()->toISOString());

        $payload = Pix::payloadLocation()->withFilters($filters)->all();

        $this->assertTrue($payload->successful());
        $this->assertEquals($response, $payload->json());
    }

    public function test_it_can_apply_filters()
    {
        Http::fake([
            'https://pix.example.com/v2/*' => Http::response([]),
        ]);

        $start = now()->subMonth()->toISOString();
        $end = now()->addMonth()->toIsoString();

        $filters = (new PayloadLocationFilters())
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

    public function test_it_can_delete_a_location()
    {
        Http::fake([
            'https://pix.example.com/v2/*' => Http::response($response = [
                'id'       => 2316,
                'location' => 'pix.example.com/qr/v2/a8534e273ecb47d3ac30613104544466',
                'tipoCob'  => 'cob',
                'criacao'  => '2020-05-31T19:39:54.013Z',
            ], 200),
        ]);

        $payload = Pix::payloadLocation()->detachChargeFromLocation(2316);

        $this->assertEquals($response, $payload->json());
        $this->assertTrue($payload->successful());
    }

    public function test_it_throws_validation_exception_if_filters_are_not_set()
    {
        $this->expectException(ValidationException::class);

        Pix::payloadLocation()->all()->json();
    }
}
