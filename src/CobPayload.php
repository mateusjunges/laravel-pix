<?php

namespace Junges\Pix;

use Junges\Pix\Concerns\InteractsWithPayload;
use Junges\Pix\Contracts\CobPayloadContract;

class CobPayload extends Payload implements CobPayloadContract
{
    use InteractsWithPayload;

    private string $url;
    private bool $unique;

    public function canBeReused(): CobPayload
    {
        $this->unique = true;

        return $this;
    }

    public function mustBeUnique(): CobPayload
    {
        $this->unique = false;

        return $this;
    }

    public function url(string $url): CobPayload
    {
        $this->url = $url;

        return $this;
    }

    public function toArray()
    {
        // TODO: Implement toArray() method.
    }

    public function getTransactionId(): string
    {
        // TODO: Implement getTransactionId() method.
    }
}