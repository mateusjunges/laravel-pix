<?php

namespace Junges\Api\Contracts;

interface PixPayloadContract
{
    public function pixKey(string $pixKey): self;

    public function description(string $description): self;

    public function merchantName(string $merchantName): self;

    public function transactionId(string $transaction_id): self;

    public function amount(string $amount): self;

    public function payload(): string;
}