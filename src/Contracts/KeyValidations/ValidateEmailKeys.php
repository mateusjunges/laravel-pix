<?php

namespace Junges\Pix\Contracts\KeyValidations;

interface ValidateEmailKeys
{
    public static function validateEmail(string $key): bool;
}
