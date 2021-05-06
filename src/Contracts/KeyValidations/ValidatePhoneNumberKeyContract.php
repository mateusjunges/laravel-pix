<?php

namespace Junges\Pix\Contracts\KeyValidations;

interface ValidatePhoneNumberKeyContract
{
    public static function validatePhoneNumber(string $phone): bool;
}