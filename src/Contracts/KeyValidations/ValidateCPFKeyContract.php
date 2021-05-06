<?php

namespace Junges\Api\Contracts;

interface ValidateCPFKeyContract
{
    public function validateCPFKey(string $cpf): bool;
}