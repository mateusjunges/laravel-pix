<?php

namespace Junges\Pix\Exceptions;

class InvalidGuiException extends PixException
{
    public static function guiCantBeEmpty()
    {
        return new static("Your bank Global Unique Identifier cant be empty.");
    }
}