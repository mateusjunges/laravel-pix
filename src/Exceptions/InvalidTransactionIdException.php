<?php

namespace Junges\Pix\Exceptions;

class InvalidTransactionIdException extends ValidationException
{
    public static function transactionIdCantBeEmpty(): PixException
    {
        return new static(__("The transaction id can't be emtpy"));
    }

    public static function invalidLengthForDynamicPayload(): PixException
    {
        return new static(__('The transaction_id length for dynamic payload should be at least 26 characters'));
    }
}
