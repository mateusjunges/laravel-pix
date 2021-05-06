<?php

namespace Junges\Pix\Concerns;

use Illuminate\Support\Str;

trait InteractsWithPayload
{
    private function getValue(string $id, ...$value): string
    {
        if (is_array($value[0])) {
            $value = implode('', $value[0]);
        } else {
            $value = implode('', $value);
        }

        $size =  Str::padLeft(
            Str::length($value),
            2,
            '0'
        );

        return "{$id}{$size}{$value}";
    }

    private function verifyCRC16($payload): string
    {
        $payload .= self::CRC16.'04';

        $polynomial = 0x1021;
        $result = 0xFFFF;

        if (($length = Str::length($payload)) > 0) {
            for ($offset = 0; $offset < $length; $offset++) {
                $result ^= (ord($payload[$offset]) << 8);
                for ($bitwise = 0; $bitwise < 8; $bitwise++) {
                    if (($result <<= 1) & 0x10000) $result ^= $polynomial;
                    $result &= 0xFFFF;
                }
            }
        }

        return self::CRC16 . "04" . Str::upper(dechex($result));
    }
}