<?php

namespace Junges\Pix\Concerns;

use Junges\Pix\Exceptions\InvalidMerchantInformationException;
use Junges\Pix\Exceptions\InvalidTransactionIdException;
use Junges\Pix\Pix;

trait InteractsWithPayload
{
    use HasCR16;

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

    protected function getAdditionalDataFieldTemplate(): string
    {
        if (empty($this->transaction_id)) {
            throw InvalidTransactionIdException::transactionIdCantBeEmpty();
        }

        $transaction_id = $this->formatValue(Pix::ADDITIONAL_DATA_FIELD_TEMPLATE_TXID, $this->transaction_id);

        return $this->formatValue(Pix::ADDITIONAL_DATA_FIELD_TEMPLATE, $transaction_id);
    }

    protected function getMerchantAccountInformation(): string
    {
        $gui = $this->formatValue(Pix::MERCHANT_ACCOUNT_INFORMATION_GUI, config('laravel-pix.gui', 'br.gov.bcb.pix'));

        $key = $this->pixKey ?? false
                ? $this->formatValue(Pix::MERCHANT_ACCOUNT_INFORMATION_KEY, $this->pixKey)
                : '';

        $description = $this->description ?? false
                ? $this->formatValue(Pix::MERCHANT_ACCOUNT_INFORMATION_DESCRIPTION, $this->description)
                : '';

        return $this->formatValue(Pix::MERCHANT_ACCOUNT_INFORMATION, $gui, $key, $description);
    }

    protected function getTransactionAmount()
    {
        return !empty($this->amount)
            ? $this->formatValue(Pix::TRANSACTION_AMOUNT, $this->amount)
            : null;
    }

    protected function getTransactionCurrency(): string
    {
        return $this->formatValue(Pix::TRANSACTION_CURRENCY, config('laravel-pix.transaction_currency_code', '986'));
    }

    protected function getCountryCode(): string
    {
        return $this->formatValue(Pix::COUNTRY_CODE, config('laravel-pix.country_code', 'BR'));
    }

    /**
     * @throws \Junges\Pix\Exceptions\PixException
     */
    protected function getMerchantName(): string
    {
        if (empty($this->merchantName)) {
            throw InvalidMerchantInformationException::merchantNameCantBeEmpty();
        }

        return $this->formatValue(Pix::MERCHANT_NAME, $this->merchantName);
    }

    protected function getMerchantCity(): string
    {
        if (empty($this->merchantName)) {
            throw InvalidMerchantInformationException::merchantCityCantBeEmpty();
        }

        return $this->formatValue(Pix::MERCHANT_CITY, $this->merchantCity);
    }

    protected function getMerchantCategoryCode(): string
    {
        return $this->formatValue(Pix::MERCHANT_CATEGORY_CODE, '0000');
    }

    protected function getPayloadFormat(): string
    {
        return $this->formatValue(Pix::PAYLOAD_FORMAT_INDICATOR, '01');
    }

    public function toStringWithoutCrc16(): string
    {
        return $this->getPayloadFormat()
            .$this->getMerchantAccountInformation()
            .$this->getMerchantCategoryCode()
            .$this->gettransactionCurrency()
            .$this->getTransactionAmount()
            .$this->getCountryCode()
            .$this->getMerchantName()
            .$this->getMerchantCity()
            .$this->getAdditionalDataFieldTemplate();
    }

    protected function buildPayload(): string
    {
        return $this->toStringWithoutCrc16().$this->getCRC16($this->toStringWithoutCrc16());
    }

    public function getPixKey(): string
    {
        return $this->pixKey;
    }
}
