<?php

namespace Junges\Pix\Tests\Payload;

use Illuminate\Support\Str;
use Junges\Pix\DynamicPayload;
use Junges\Pix\Tests\TestCase;

class DynamicPayloadTest extends TestCase
{
    private string $transactionId;

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->transactionId = Str::random(26);
    }

    public function test_it_can_format_the_payload()
    {
        $payload = (new DynamicPayload())
            ->transactionId($this->transactionId)
            ->mustBeUnique()
            ->url('pix.example.com/8b3da2f39a4140d1a91abd93113bd441')
            ->merchantName('Fulano de Tal')
            ->merchantCity('BRASILIA');

        $crc16 = $payload->getCRC16($payload->toStringWithoutCrc16());

        $expected = "00020101021226700014br.gov.bcb.pix2548pix.example.com/8b3da2f39a4140d1a91abd93113bd4415204000053039865802BR5913Fulano de Tal6008BRASILIA62300526{$this->transactionId}{$crc16}";

        $this->assertEquals($expected, $payload->getPayload());
    }

    public function test_it_removes_protocol_from_url()
    {
        $payload = (new DynamicPayload())
            ->transactionId($this->transactionId)
            ->mustBeUnique()
            ->url('https://pix.example.com/8b3da2f39a4140d1a91abd93113bd441')
            ->merchantName('Fulano de Tal')
            ->merchantCity('BRASILIA');

        $this->assertStringNotContainsString('http', $payload->getPayload());
        $this->assertStringNotContainsString('https', $payload->getPayload());
    }
}
