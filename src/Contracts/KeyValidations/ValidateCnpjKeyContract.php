<?php

namespace Junges\Pix\Contracts;

interface ValidateCnpjKeyContract
{
    public function validateCnpjKey(string $cnpj): bool;
}