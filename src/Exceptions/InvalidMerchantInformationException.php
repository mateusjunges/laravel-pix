<?php

namespace Junges\Pix\Exceptions;

class InvalidMerchantInformationException extends PixException
{
    public static function merchantNameCantBeEmpty(): PixException
    {
        return new static(__("The merchant_name can't be empty."));
    }

    public static function merchantCityCantBeEmpty(): PixException
    {
        return new static(__("The merchant city can't be empty."));
    }
}
