<?php

namespace Junges\Pix\Events\ReceivedPix;

class RefundRequestedEvent
{
    public array $refund;

    public function __construct(array $refund)
    {
        $this->refund = $refund;
    }
}