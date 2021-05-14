<?php

namespace Junges\Pix\Api\Contracts;

interface ConsumesReceivedPixEndpoints extends ConsumesPixApi
{
    public function getBye2eid(string $e2eid);

    public function all();

    public function consultRefund(string $e2eid, string $refundId);

    public function refund(string $e2eid, string $refundId);
}
