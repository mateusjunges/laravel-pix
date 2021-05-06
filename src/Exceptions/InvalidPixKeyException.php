<?php

namespace Junges\Pix\Exceptions;

class InvalidPixKeyException extends PixException
{
    public static function keyCantBeEmpty(): PixException
    {
        return new static(__("Your pix key can't be empty."));
    }

    public static function invalidKeyType(string $type): PixException
    {
        return new static("Your pix key type '{$type}' is not valid.");
    }
}