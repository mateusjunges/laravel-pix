<?php

namespace Junges\Pix\Contracts;

interface DynamicPayloadContract extends PixPayloadContract
{
    public function getTransactionId(): string;

    public function merchantName(string $merchantName): self;

    public function transactionId(string $transaction_id): self;
}