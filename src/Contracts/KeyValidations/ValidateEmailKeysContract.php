<?php

namespace Junges\Pix\Contracts\KeyValidations;

interface ValidateEmailKeysContract
{
    public static function validateEmail(string $key): bool;
}