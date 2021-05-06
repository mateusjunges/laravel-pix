<?php

namespace Junges\Pix;

use Junges\Pix\Concerns\InteractsWithPayload;
use Junges\Pix\Concerns\ValidatePixKeys;
use Junges\Pix\Contracts\KeyValidations\ValidateCnpjKeyContract;
use Junges\Pix\Contracts\KeyValidations\ValidateCPFKeyContract;
use Junges\Pix\Contracts\KeyValidations\ValidateEmailKeysContract;
use Junges\Pix\Contracts\KeyValidations\ValidateRandomPixKeysContract;
use Junges\Pix\Contracts\PixPayloadContract;

class Payload implements PixPayloadContract,
    ValidateRandomPixKeysContract,
    ValidateCnpjKeyContract,
    ValidateCPFKeyContract,
    ValidateEmailKeysContract
{
    use InteractsWithPayload;
    use ValidatePixKeys;

    private string $pixKey;
    private string $description;
    private string $merchantName;
    private string $merchantCity;
    private string $transaction_id;
    private string $amount;

    public function pixKey(string $pixKey): Payload
    {
        $this->pixKey = $pixKey;

        return $this;
    }

    public function description(string $description): Payload
    {
        $this->description = $description;

        return $this;
    }

    public function merchantName(string $merchantName): Payload
    {
        $this->merchantName = $merchantName;

        return $this;
    }

    public function transactionId(string $transaction_id): Payload
    {
        $this->transaction_id = $transaction_id;

        return $this;
    }

    public function amount(string $amount): Payload
    {
        $this->amount = $amount;

        return $this;
    }

    public function merchantCity(string $merchantCity): Payload
    {
        $this->merchantCity = $merchantCity;
        return $this;
    }

    /**
     * @return string
     * @throws Exceptions\PixException
     */
    public function payload(): string
    {
        $this->validatePayload();

        return $this->buildPayload();
    }
}