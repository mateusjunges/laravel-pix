<?php

namespace Junges\Api\Contracts;

interface ValidateRandomPixKeysContract
{
    public function validateRandomKey(string $key): bool;
}