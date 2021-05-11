<?php

namespace Junges\Pix\Contracts;

interface DynamicPayloadContract
{
    public function getTransactionId(): string;

    public function merchantName(string $merchantName): self;

    public function transactionId(string $transaction_id): self;

    public function getPayload(): string;

}