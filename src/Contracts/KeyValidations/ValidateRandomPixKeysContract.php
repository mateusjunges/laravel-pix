<?php

namespace Junges\Pix\Contracts\KeyValidations;

interface ValidateRandomPixKeysContract
{
    public function validateRandomKey(string $key): bool;
}