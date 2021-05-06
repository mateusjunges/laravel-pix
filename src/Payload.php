<?php

namespace Junges\Pix;

use Illuminate\Support\Str;

class Payload
{
    const PAYLOAD_FORMAT_INDICATOR = '00';
    const MERCHANT_ACCOUNT_INFORMATION = '26';
    const MERCHANT_ACCOUNT_INFORMATION_GUI = '00';
    const MERCHANT_ACCOUNT_INFORMATION_KEY = '01';
    const MERCHANT_ACCOUNT_INFORMATION_DESCRIPTION = '02';
    const MERCHANT_CATEGORY_CODE = '52';
    const TRANSACTION_CURRENCY = '53';
    const TRANSACTION_AMOUNT = '54';
    const COUNTRY_CODE = '58';
    const MERCHANT_NAME = '59';
    const MERCHANT_CITY = '60';
    const ADDITIONAL_DATA_FIELD_TEMPLATE = '62';
    const ADDITIONAL_DATA_FIELD_TEMPLATE_TXID = '05';
    const CRC16 = '63';

    private string $pixKey;
    private string $description;
    private string $merchantName;
    private string $transaction_id;
    private string $amount;

    public function pixKey(string $pixKey): Payload
    {
        $this->pixKey = $pixKey;

        return $this;
    }

    public function description(string $description): Payload
    {
        $this->description = $description;

        return $this;
    }

    public function merchantName(string $merchantName): Payload
    {
        $this->merchantName = $merchantName;

        return $this;
    }

    public function transactionId(string $transaction_id): Payload
    {
        $this->transaction_id = $transaction_id;

        return $this;
    }

    public function amount(string $amount): Payload
    {
        $this->amount = $amount;

        return $this;
    }

    public function payload(): string
    {
        return $this->getValue(self::PAYLOAD_FORMAT_INDICATOR, '01');
    }

    private function getMerchantAccountInformation(): string
    {
        $gui = $this->getValue(self::MERCHANT_ACCOUNT_INFORMATION_GUI, 'br.gov.bcb.pix');

        $key = $this->getValue(self::MERCHANT_ACCOUNT_INFORMATION_KEY, $this->pixKey);

        if ($this->description) {
            $description = $this->getValue(self::MERCHANT_ACCOUNT_INFORMATION_DESCRIPTION, $this->description);
        }

        return $this->getValue(self::MERCHANT_ACCOUNT_INFORMATION, $gui, $key, $description ?? null);
    }

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

    private function getCRC16($payload) {
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