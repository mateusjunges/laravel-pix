<?php

namespace Junges\Pix\Tests\Payload;

use Illuminate\Support\Str;
use Junges\Pix\Payload;
use Junges\Pix\Tests\TestCase;

class PayloadTest extends TestCase
{
    private string $transactionId;

    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->transactionId = Str::random();
    }

    public function test_it_can_formats_the_payload()
    {
        $payload = (new Payload())
            ->transactionId($this->transactionId)
            ->pixKey($this->randomKey)
            ->merchantName('Fulano de Tal')
            ->merchantCity('BRASILIA');

        $crc16 = $payload->getCRC16($payload->toStringWithoutCrc16());

        $expected = "00020126580014br.gov.bcb.pix0136{$this->randomKey}5204000053039865802BR5913Fulano de Tal6008BRASILIA62200516{$this->transactionId}{$crc16}";

        $this->assertEquals($expected, $payload->getPayload());
    }

    public function test_it_can_set_pix_description()
    {
        $payload = (new Payload())
            ->transactionId($this->transactionId)
            ->pixKey($this->randomKey)
            ->merchantName('Fulano de Tal')
            ->description('Test description')
            ->merchantCity('BRASILIA');

        $crc16 = $payload->getCRC16($payload->toStringWithoutCrc16());
        $expected = "00020126780014br.gov.bcb.pix01367d9f0335-8dcc-4054-9bf9-0dbd61d369060216Test description5204000053039865802BR5913Fulano de Tal6008BRASILIA62200516{$this->transactionId}{$crc16}";

        $this->assertEquals($expected, $payload->getPayload());
    }

    public function test_it_can_set_pix_amount()
    {
        $payload = (new Payload())
            ->transactionId($this->transactionId)
            ->pixKey($this->randomKey)
            ->merchantName('Fulano de Tal')
            ->description('Test description')
            ->amount('894.87')
            ->merchantCity('BRASILIA');

        $crc16 = $payload->getCRC16($payload->toStringWithoutCrc16());
        $expected = "00020126780014br.gov.bcb.pix01367d9f0335-8dcc-4054-9bf9-0dbd61d369060216Test description5204000053039865406894.875802BR5913Fulano de Tal6008BRASILIA62200516{$this->transactionId}{$crc16}";

        $this->assertEquals($expected, $payload->getPayload());
    }
}
