<?php

namespace Junges\Pix;

use Illuminate\Support\Str;
use Junges\Pix\Concerns\InteractsWithPayload;
use Junges\Pix\Contracts\DynamicPayloadContract;
use Junges\Pix\Exceptions\InvalidTransactionIdException;

class DynamicPayload extends Payload implements DynamicPayloadContract
{
    use InteractsWithPayload;

    private string $url;
    private bool $unique;

    public function transactionId(string $transaction_id): Payload
    {
        throw_if(
            Str::length($transaction_id) < Pix::MAX_TRANSACTION_ID_LENGTH,
            InvalidTransactionIdException::invalidLengthForDynamicPayload()
        );

        $this->transaction_id = $transaction_id;

        return $this;
    }

    public function canBeReused(): DynamicPayload
    {
        $this->unique = true;

        return $this;
    }

    public function mustBeUnique(): DynamicPayload
    {
        $this->unique = false;

        return $this;
    }

    public function url(string $url): DynamicPayload
    {
        $this->url = $url;

        return $this;
    }

    public function getTransactionId(): string
    {
        return $this->transaction_id;
    }
}