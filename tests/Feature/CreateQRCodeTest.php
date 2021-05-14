<?php

namespace Junges\Pix\Tests\Feature;

use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Junges\Pix\Tests\TestCase;

class CreateQRCodeTest extends TestCase
{
    public function test_it_validates_the_request()
    {
        $expected = [
            'message' => 'The given data was invalid.',
            'errors'  => [
                'transaction_id' => [
                    'The transaction id field is required.',
                ],
                'merchant_city' => [
                    'The merchant city field is required.',
                ],
                'amount' => [
                    'The amount field is required.',
                ],
            ],
        ];

        $response = $this->json('GET', 'laravel-pix/pix/create', [
            'key'           => $this->randomKey,
            'merchant_name' => 'Mateus Junges',
        ]);

        $responseArray = is_array($response->decodeResponseJson())
            ? $response->decodeResponseJson()
            : $response->decodeResponseJson()->json();

        $this->assertIsArray($responseArray['errors']);
        $this->assertEquals(json_encode($expected), json_encode($responseArray));
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

        $response = $this->json('GET', 'laravel-pix/pix/create', [
            'key'           => $this->randomKey,
            'merchant_name' => 'Mateus Junges',
            'merchant_city' => 'Ponta Grossa',
        ]);

        $expected = [
            'message' => 'The given data was invalid.',
            'errors'  => [
                'transaction_id' => [
                    'The transaction id field is required.',
                ],
                'amount' => [
                    'The amount field is required.',
                ],
            ],
        ];

        $responseArray = is_array($response->decodeResponseJson())
            ? $response->decodeResponseJson()
            : $response->decodeResponseJson()->json();

        $this->assertIsArray($responseArray['errors']);
        $this->assertEquals(json_encode($expected), json_encode($responseArray));
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function test_it_can_create_a_qr_code_without_optional_data()
    {
        $response = $this->json('GET', 'laravel-pix/pix/create', [
            'key'            => $this->randomKey,
            'transaction_id' => Str::random(),
            'merchant_city'  => 'Ponta Grossa',
            'merchant_name'  => 'Mateus Junges',
            'amount'         => '10.00',
        ]);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'payload',
            'base64_qr_code',
        ]);
    }

    public function test_it_can_create_a_qr_code_with_optional_data()
    {
        $response = $this->json('GET', 'laravel-pix/pix/create', [
            'key'            => $this->randomKey,
            'transaction_id' => Str::random(),
            'merchant_city'  => 'Ponta Grossa',
            'merchant_name'  => 'Mateus Junges',
            'description'    => 'Test description',
            'amount'         => '10.00',
        ]);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJsonStructure([
            'payload',
            'base64_qr_code',
        ]);
    }
}
