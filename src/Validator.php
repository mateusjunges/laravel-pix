<?php

namespace Junges\Pix;

use Illuminate\Support\Str;
use Junges\Pix\Contracts\KeyValidations\PerformKeyValidations;
use Junges\Pix\Exceptions\InvalidPixKeyException;

class Validator implements PerformKeyValidations
{
    public static function validateRandom(string $key): bool
    {
        return preg_match('/^[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i', $key);
    }

    public static function validateCPF(string $cpf): bool
    {
        $cpf = preg_replace('/[^0-9]/', '', $cpf);

        if (Str::length($cpf) !== 11) {
            return false;
        }

        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }

            $d = ((10 * $d) % 11) % 10;

            if ($cpf[$c] != $d) {
                return false;
            }
        }

        return true;
    }

    public static function validateCnpj(string $cnpj): bool
    {
        if (empty($cnpj)) {
            return false;
        }

        $cnpj = preg_replace('/[^0-9]/', '', $cnpj);

        if (Str::length($cnpj) !== 14) {
            return false;
        }

        $cnpj = str_pad($cnpj, 14, 0, STR_PAD_LEFT);

        $j = 5;
        $k = 6;
        $sum_1 = 0;
        $sum_2 = 0;

        for ($i = 0; $i < 13; $i++) {
            $j = $j == 1 ? 9 : $j;
            $k = $k == 1 ? 9 : $k;

            $sum_2 += ($cnpj[$i] * $k);

            if ($i < 12) {
                $sum_1 += ($cnpj[$i] * $j);
            }

            $k--;
            $j--;
        }

        $first_digit = $sum_1 % 11 < 2 ? 0 : 11 - $sum_1 % 11;
        $second_digit = $sum_2 % 11 < 2 ? 0 : 11 - $sum_2 % 11;

        return ($cnpj[12] == $first_digit) && ($cnpj[13] == $second_digit);
    }

    public static function validateEmail(string $key): bool
    {
        $key = str_replace(' ', '@', $key);

        return filter_var($key, FILTER_VALIDATE_EMAIL);
    }

    public static function validatePhoneNumber(string $phone): bool
    {
        $phone = str_replace(config('laravel-pix.country_phone_prefix', '+55'), '', $phone);
        $phone = preg_replace('/[^\d]+/', '', $phone);
        $phone = config('laravel-pix.country_phone_prefix', '+55').$phone;

        if (!preg_match('/^(\+55)?(\d{10,11})$/', $phone)) {
            return false;
        }

        return true;
    }

    /**
     * @throws Exceptions\PixException
     */
    public static function validate(string $type, string $key): bool
    {
        if (!in_array($type, Pix::KEY_TYPES)) {
            throw InvalidPixKeyException::invalidKeyType($type);
        }

        switch ($type) {
            case Pix::RANDOM_KEY_TYPE:
                return Validator::validateRandom($key);
            case Pix::CPF_KEY_TYPE:
                return Validator::validateCPF($key);
            case Pix::CNPJ_KEY_TYPE:
                return Validator::validateCnpj($key);
            case Pix::EMAIL_KEY_TYPE:
                return Validator::validateEmail($key);
            case Pix::PHONE_NUMBER_KEY_TYPE:
                return Validator::validatePhoneNumber($key);
            default:
                throw InvalidPixKeyException::invalidKeyType($type);
        }
    }
}
