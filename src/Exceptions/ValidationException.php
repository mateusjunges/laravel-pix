<?php

namespace Junges\Pix\Exceptions;

class ValidationException extends PixException
{
    public static function invalidStartAndEndFields()
    {
        return new static("Os campos 'inicio' e 'fim' são obrigatórios.");
    }
}