<?php

namespace Junges\Pix\Exceptions;

class InvalidDebtorException extends PixException
{
    public static function debtorWithCpfMustContainACpfKey()
    {
        return new static(__("Your debtor array must contain a 'cpf' key."));
    }

    public static function debtorWithCnpjfMustContainACnpjKey()
    {
        return new static(__("Your debtor array must contain a 'cnpj' key."));
    }
}