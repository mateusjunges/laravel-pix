<?php

namespace Junges\Pix\Exceptions;

class ValidationException extends PixException
{
    public static function invalidStartAndEndFields()
    {
        return new static("Os campos 'inicio' e 'fim' são obrigatórios.");
    }

    public static function filtersAreRequired()
    {
        return new static('Não é possível realizar esta requisição sem utilizar filtros.');
    }
}
