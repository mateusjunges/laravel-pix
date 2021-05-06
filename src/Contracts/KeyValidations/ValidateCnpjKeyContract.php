<?php

namespace Junges\Pix\Contracts\KeyValidations;

interface ValidateCnpjKeyContract
{
    public function validateCnpjKey(string $cnpj): bool;
}