<?php

namespace Junges\Pix\Contracts\KeyValidations;

interface PerformKeyValidations extends ValidateCnpjKey,
    ValidateCPFKey,
    ValidateEmailKeys,
    ValidateRandomKeys,
    ValidatePhoneNumberKeys
{
    public static function validate(string $type, string $key): bool;
}
