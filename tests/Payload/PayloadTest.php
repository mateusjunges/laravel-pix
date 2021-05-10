<?php

namespace Junges\Pix\Tests\Payload;

use Illuminate\Support\Str;
use Junges\Pix\Payload;
use Junges\Pix\Tests\TestCase;

class PayloadTest extends TestCase
{
    public function test_it_can_formats_the_payload()
    {
        $transactionId = Str::random();

        $payload = (new Payload())
            ->transactionId($transactionId)
            ->pixKey($this->randomKey)
            ->merchantName("Fulano de Tal")
            ->merchantCity("BRASILIA");

        $crc16 = $payload->getCRC16($payload->toStringWithoutCrc16());

        $expected = "00020126580014br.gov.bcb.pix0136{$this->randomKey}5204000053039865802BR5913Fulano de Tal6008BRASILIA62200516{$transactionId}{$crc16}";

        $this->assertEquals($expected, $payload->getPayload());
    }
}