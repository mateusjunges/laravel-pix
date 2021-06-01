<?php

namespace Junges\Pix;

use Illuminate\Support\Str;
use Junges\Pix\Concerns\InteractsWithDynamicPayload;
use Junges\Pix\Contracts\PixPayloadContract;
use Junges\Pix\Exceptions\InvalidTransactionIdException;

class DynamicPayload implements PixPayloadContract
{
    use InteractsWithDynamicPayload;

    protected string $merchantName;
    protected string $merchantCity;
    protected string $transaction_id;
    private string $url;
    private bool $unique;

    public function transactionId(string $transaction_id): DynamicPayload
    {
        throw_if(
            Str::length($transaction_id) < Pix::MIN_TRANSACTION_ID_LENGTH,
            InvalidTransactionIdException::invalidLengthForDynamicPayload()
        );

        $this->transaction_id = $transaction_id;

        return $this;
    }

    public function merchantName(string $merchantName): DynamicPayload
    {
        $this->merchantName = Str::length($merchantName) > Pix::MAX_MERCHANT_NAME_LENGTH
            ? substr($merchantName, 0, Pix::MAX_MERCHANT_NAME_LENGTH)
            : $merchantName;

        return $this;
    }

    public function merchantCity(string $merchantCity): DynamicPayload
    {
        $this->merchantCity = Str::length($merchantCity) > Pix::MAX_MERCHANT_CITY_LENGTH
            ? substr($merchantCity, 0, Pix::MAX_MERCHANT_CITY_LENGTH)
            : $merchantCity;

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

    public function getPayload(): string
    {
        return $this->buildPayload();
    }
}
