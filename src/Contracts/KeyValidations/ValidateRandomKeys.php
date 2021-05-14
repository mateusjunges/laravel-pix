<?php

namespace Junges\Pix\Contracts\KeyValidations;

interface ValidateRandomKeys
{
    public static function validateRandom(string $key): bool;
}
