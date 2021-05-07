<?php

namespace Junges\Pix\Exceptions;

class InvalidAmountException extends PixException
{
    public static function amountCantBeEmpty(): PixException
    {
        return new static(__("The transaction amount can't be empty."));
    }

    public static function invalidPattern(): PixException
    {
        return new static(__("Invalid amount pattern. It should match with ") . "\"^[0-9]{1,10}.[0-9]{2}$\"");
    }
}