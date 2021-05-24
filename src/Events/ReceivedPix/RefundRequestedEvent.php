<?php

namespace Junges\Pix\Events\ReceivedPix;

class RefundRequestedEvent
{
    public array $refund;
    public string $e2eid;
    public string $refundId;

    public function __construct(array $refund, string $e2eid, string $refundId)
    {
        $this->refund = $refund;
        $this->e2eid = $e2eid;
        $this->refundId = $refundId;
    }
}
