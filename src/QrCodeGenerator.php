<?php

namespace Junges\Pix;

use Junges\Pix\Contracts\DynamicPayloadContract;
use Junges\Pix\Contracts\GeneratesQrCodeContract;
use Junges\Pix\Contracts\PixPayloadContract;
use Mpdf\QrCode\Output\Png;
use Mpdf\QrCode\QrCode;

class QrCodeGenerator implements GeneratesQrCodeContract
{
    /**
     * @throws \Mpdf\QrCode\QrCodeException
     * @throws Exceptions\PixException
     */
    public function withPayload(PixPayloadContract $payload): string
    {
        $qrCode = new QrCode($payload->getPayload());

        $png = (new Png())->output($qrCode, config('laravel-pix.qr_code_size', 100));

        return base64_encode($png);
    }

    /**
     * @throws Exceptions\PixException
     * @throws \Mpdf\QrCode\QrCodeException
     */
    public function withDynamicPayload(DynamicPayloadContract $payload): string
    {
        return $this->withPayload($payload);
    }
}