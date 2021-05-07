<?php

namespace Junges\Pix;

use Junges\Pix\Concerns\InteractsWithPayload;
use Junges\Pix\Contracts\DynamicPayloadContract;

class DynamicPayload extends Payload implements DynamicPayloadContract
{
    use InteractsWithPayload;

    private string $url;
    private bool $unique;

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

    public function toArray()
    {
        // TODO: Implement toArray() method.
    }

    public function getTransactionId(): string
    {
        return $this->transaction_id;
    }
}