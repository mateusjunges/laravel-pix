<?php

namespace Junges\Pix\Concerns;

trait FormatPayloadValues
{
    protected function formatValue(string $id, ...$value): string
    {
        if (is_array($value[0])) {
            $value = implode('', $value[0]);
        } else {
            $value = implode('', $value);
        }

        $size = str_pad(
            mb_strlen($value),
            2,
            '0',
            STR_PAD_LEFT
        );

        return "{$id}{$size}{$value}";
    }
}
