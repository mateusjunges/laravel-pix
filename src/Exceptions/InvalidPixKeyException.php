<?php

namespace Junges\Pix\Exceptions;

class InvalidPixKeyException extends PixException
{
    public static function keyCantBeEmpty(): PixException
    {
        return new static(__("Your pix key can't be empty."));
    }
}