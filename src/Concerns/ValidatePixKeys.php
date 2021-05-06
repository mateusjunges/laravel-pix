<?php

namespace Junges\Pix\Concerns;

use Illuminate\Support\Str;

trait ValidatePixKeys
{
    public function validateRandomKey(string $key): bool
    {
        return preg_match('/^[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i', $key);
    }

    public function validateCPFKey(string $cpf): bool
    {
        $cpf = preg_replace("/[^0-9]/", "", $cpf);

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

    public function validateCnpjKey(string $cnpj): bool
    {
        if (empty($cnpj)) {
            return false;
        }

        $cnpj = preg_replace("/[^0-9]/", "", $cnpj);
        $cnpj = str_pad($cnpj, 14, 0, STR_PAD_LEFT);

        if (Str::length($cnpj) !== 14) {
            return false;
        }

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

        return (($cnpj[12] == $first_digit) && ($cnpj[13] == $second_digit));
    }

    public function validateEmailKey(string $email): bool
    {
        $email = str_replace(' ', '@', $email);

        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }
}
