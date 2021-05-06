<?php

namespace Junges\Pix;

use Illuminate\Support\Str;
use Junges\Pix\Concerns\InteractsWithPayload;
use Junges\Pix\Contracts\PixPayloadContract;

class Payload implements PixPayloadContract
{
    use InteractsWithPayload;

    private string $pixKey;
    private string $description;
    private string $merchantName;
    private string $merchantCity;
    private string $transaction_id;
    private string $amount;
    private bool $reusable;

    /**
     * @param string $pixKey
     * @return $this
     * @throws Exceptions\PixException
     */
    public function pixKey(string $pixKey): Payload
    {
        $this->pixKey = Parser::parse($pixKey);

        return $this;
    }

    public function description(string $description): Payload
    {
        $this->description = Str::length($description) > Pix::MAX_DESCRIPTION_LENGTH
            ? substr($description, 0, Pix::MAX_DESCRIPTION_LENGTH)
            : $description;

        return $this;
    }

    public function merchantName(string $merchantName): Payload
    {
        $this->merchantName = Str::length($merchantName) > Pix::MAX_MERCHANT_NAME_LENGTH
            ? substr($merchantName, 0, Pix::MAX_MERCHANT_NAME_LENGTH)
            : $merchantName;

        return $this;
    }

    public function merchantCity(string $merchantCity): Payload
    {
        $this->merchantCity = Str::length($merchantCity) > Pix::MAX_MERCHANT_CITY_LENGTH
            ? substr($merchantCity, 0, Pix::MAX_MERCHANT_CITY_LENGTH)
            : $merchantCity;

        return $this;
    }

    public function transactionId(string $transaction_id): Payload
    {
        $this->transaction_id = Str::length($transaction_id) > Pix::MAX_TRANSACTION_ID_LENGTH
            ? substr($transaction_id, 0, Pix::MAX_TRANSACTION_ID_LENGTH)
            : $transaction_id;

        return $this;
    }

    public function amount(string $amount): Payload
    {
        $this->amount = $amount;

        return $this;
    }

    public function canBeReused(): Payload
    {
        $this->reusable = true;

        return $this;
    }

    public function canNotBeReused(): Payload
    {
        $this->reusable = false;

        return $this;
    }

    /**
     * @return string
     * @throws Exceptions\PixException
     */
    public function getPayload(): string
    {
        $this->validatePayload();

        return $this->buildPayload();
    }
}