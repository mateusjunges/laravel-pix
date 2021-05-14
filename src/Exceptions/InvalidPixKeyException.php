<?php

namespace Junges\Pix\Exceptions;

class InvalidPixKeyException extends ValidationException
{
    public static function invalidKeyType(string $type): PixException
    {
        return new static("Your pix key type '{$type}' is not valid.");
    }
}
