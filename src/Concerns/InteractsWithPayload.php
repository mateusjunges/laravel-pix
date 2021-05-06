<?php

namespace Junges\Pix\Concerns;

use Illuminate\Support\Str;

trait InteractsWithPayload
{
    private function formatValue(string $id, ...$value): string
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

    private function getAdditionalDataFieldTemplate(): string
    {
        $transaction_id = $this->formatValue(self::ADDITIONAL_DATA_FIELD_TEMPLATE_TXID, $this->transaction_id);

        return $this->formatValue(self::ADDITIONAL_DATA_FIELD_TEMPLATE, $transaction_id);
    }

    private function getMerchantAccountInformation(): string
    {
        $gui = $this->formatValue(self::MERCHANT_ACCOUNT_INFORMATION_GUI, config('laravel-pix.gui', 'br.gov.bcb.pix'));

        $key = $this->formatValue(self::MERCHANT_ACCOUNT_INFORMATION_KEY, $this->pixKey);

        if ($this->description) {
            $description = $this->formatValue(self::MERCHANT_ACCOUNT_INFORMATION_DESCRIPTION, $this->description);
        }

        return $this->formatValue(self::MERCHANT_ACCOUNT_INFORMATION, $gui, $key, $description ?? null);
    }

    private function buildPayload(): string
    {
        return $this->formatValue(self::PAYLOAD_FORMAT_INDICATOR, '01')
            . $this->getMerchantAccountInformation()
            . $this->formatValue(self::MERCHANT_CATEGORY_CODE, '0000')
            . $this->formatValue(self::TRANSACTION_CURRENCY, config('laravel-pix.currency_code', '986'))
            . $this->formatValue(self::TRANSACTION_AMOUNT, $this->amount)
            . $this->formatValue(self::COUNTRY_CODE, config('laravel-pix.country_code', 'BR'))
            . $this->formatValue(self::MERCHANT_NAME, $this->merchantName)
            . $this->formatValue(self::MERCHANT_CITY, $this->merchantCity)
            . $this->getAdditionalDataFieldTemplate();

    }
}