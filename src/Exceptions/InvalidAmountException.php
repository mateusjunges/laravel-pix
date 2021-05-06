<?php

namespace Junges\Pix\Exceptions;

class InvalidAmountException extends PixException
{
    public static function amountCantBeEmpty(): PixException
    {
        return new static(__("The transaction amount can't be empty."));
    }
}