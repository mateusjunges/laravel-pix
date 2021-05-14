<?php

namespace Junges\Pix;

use Junges\Pix\Contracts\GeneratesQrCode;
use Junges\Pix\Contracts\PixPayloadContract;
use Mpdf\QrCode\Output\Png;
use Mpdf\QrCode\QrCode;

class QrCodeGenerator implements GeneratesQrCode
{
    /**
     * @throws \Mpdf\QrCode\QrCodeException
     */
    public function withPayload(PixPayloadContract $payload): string
    {
        $qrCode = new QrCode($payload->getPayload());

        $png = (new Png())->output($qrCode, config('laravel-pix.qr_code_size', 100));

        return base64_encode($png);
    }
}
