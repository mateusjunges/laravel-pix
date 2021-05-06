<?php

namespace Junges\Pix\Contracts\KeyValidations;

interface PerformKeyValidationsContract extends ValidateCnpjKeyContract,
    ValidateCPFKeyContract,
    ValidateEmailKeysContract,
    ValidateRandomPixKeysContract,
    ValidatePhoneNumberKeyContract
{
    public static function validate(string $type, string $key): bool;
}
