<?php

namespace Junges\Pix\Contracts\KeyValidations;

interface ValidateCnpjKeyContract
{
    public static function validateCnpj(string $cnpj): bool;
}