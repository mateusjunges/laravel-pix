<?php

namespace Junges\Pix\Contracts\KeyValidations;

interface ValidateCPFKeyContract
{
    public function validateCPFKey(string $cpf): bool;
}