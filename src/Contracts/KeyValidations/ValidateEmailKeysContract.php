<?php

namespace Junges\Pix\Contracts\KeyValidations;

interface ValidateEmailKeysContract
{
    public function validateEmailKey(string $key): bool;
}