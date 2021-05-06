<?php

namespace Junges\Pix\Contracts\KeyValidations;

interface ValidateRandomPixKeysContract
{
    public static function validateRandom(string $key): bool;
}