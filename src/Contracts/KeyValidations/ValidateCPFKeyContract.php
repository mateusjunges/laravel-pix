<?php

namespace Junges\Pix\Contracts\KeyValidations;

interface ValidateCPFKeyContract
{
    public static function validateCPF(string $cpf): bool;
}