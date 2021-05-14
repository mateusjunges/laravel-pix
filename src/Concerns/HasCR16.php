<?php

namespace Junges\Pix\Concerns;

use Illuminate\Support\Str;
use Junges\Pix\Pix;

trait HasCR16
{
    public function getCRC16($payload): string
    {
        $payload .= Pix::CRC16.Pix::CRC16_LENGTH;

        $polynomial = 0x1021;
        $result = 0xFFFF;

        if (($length = Str::length($payload)) > 0) {
            for ($offset = 0; $offset < $length; $offset++) {
                $result ^= (ord($payload[$offset]) << 8);
                for ($bitwise = 0; $bitwise < 8; $bitwise++) {
                    if (($result <<= 1) & 0x10000) {
                        $result ^= $polynomial;
                    }
                    $result &= 0xFFFF;
                }
            }
        }

        return Pix::CRC16.Pix::CRC16_LENGTH.Str::upper(dechex($result));
    }
}
