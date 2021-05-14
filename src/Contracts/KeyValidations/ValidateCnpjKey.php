<?php

namespace Junges\Pix\Contracts\KeyValidations;

interface ValidateCnpjKey
{
    public static function validateCnpj(string $cnpj): bool;
}
