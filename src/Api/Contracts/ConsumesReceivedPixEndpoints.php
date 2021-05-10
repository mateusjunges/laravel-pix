<?php

namespace Junges\Pix\Api\Contracts;

interface ConsumesReceivedPixEndpoints extends ConsumesPixApi
{
    public function getBye2eid(string $e2eid): array;

    public function all(): array;

    public function consultRefund(string $e2eid, string $refundId): array;

    public function refund(string $e2eid, string $refundId): array;

}