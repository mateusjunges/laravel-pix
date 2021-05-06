<?php

namespace Junges\Pix\Exceptions;

class InvalidTransactionIdException extends PixException
{
    public static function transactionIdCantBeEmpty(): PixException
    {
        return new static(__("The transaction id can't be emtpy"));
    }
}