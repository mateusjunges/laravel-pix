<?php

namespace Junges\Pix\Concerns;

use Illuminate\Support\Str;
use Junges\Pix\Exceptions\InvalidAmountException;
use Junges\Pix\Exceptions\InvalidMerchantInformationException;
use Junges\Pix\Exceptions\InvalidPixKeyException;
use Junges\Pix\Pix;

trait InteractsWithPayload
{
    use HasCR16;

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
        $transaction_id = $this->formatValue(Pix::ADDITIONAL_DATA_FIELD_TEMPLATE_TXID, $this->transaction_id);

        return $this->formatValue(Pix::ADDITIONAL_DATA_FIELD_TEMPLATE, $transaction_id);
    }

    private function getMerchantAccountInformation(): string
    {
        $gui = $this->formatValue(Pix::MERCHANT_ACCOUNT_INFORMATION_GUI, config('laravel-pix.gui', 'br.gov.bcb.pix'));

        $key = $this->formatValue(Pix::MERCHANT_ACCOUNT_INFORMATION_KEY, $this->pixKey);

        if ($this->description) {
            $description = $this->formatValue(Pix::MERCHANT_ACCOUNT_INFORMATION_DESCRIPTION, $this->description);
        }

        return $this->formatValue(Pix::MERCHANT_ACCOUNT_INFORMATION, $gui, $key, $description ?? null);
    }

    private function getTransactionAmount(): string
    {
        return $this->formatValue(Pix::TRANSACTION_AMOUNT, $this->amount);
    }

    private function getTransactionCurrency(): string
    {
        return $this->formatValue(Pix::TRANSACTION_CURRENCY, config('laravel-pix.currency_code', '986'));
    }

    private function getPointOfInitializationMethod(): string
    {
        return $this->reusable
            ? $this->formatValue(Pix::POINT_OF_INITIATION_METHOD, '11')
            : $this->formatValue(Pix::POINT_OF_INITIATION_METHOD, '12');
    }

    private function getCountryCode(): string
    {
        return $this->formatValue(Pix::COUNTRY_CODE, config('laravel-pix.country_code', 'BR'));
    }

    private function getMerchantName(): string
    {
        return $this->formatValue(Pix::MERCHANT_NAME, $this->merchantName);
    }

    private function getMerchantCity(): string
    {
        return $this->formatValue(Pix::MERCHANT_CITY, $this->merchantCity);
    }

    private function getMerchantCategoryCode(): string
    {
        return $this->formatValue(Pix::MERCHANT_CATEGORY_CODE, '0000');
    }

    private function getPayloadFormat(): string
    {
        return $this->formatValue(Pix::PAYLOAD_FORMAT_INDICATOR, '01');
    }

    private function buildPayload(): string
    {
        $payload = $this->getPayloadFormat()
            . $this->getPointOfInitializationMethod()
            . $this->getMerchantAccountInformation()
            . $this->getMerchantCategoryCode()
            . $this->gettransactionCurrency()
            . $this->getTransactionAmount()
            . $this->getCountryCode()
            . $this->getMerchantName()
            . $this->getMerchantCity()
            . $this->getAdditionalDataFieldTemplate();

        return $payload . $this->getCRC16($payload);
    }

    /**
     * @throws \Junges\Pix\Exceptions\PixException
     */
    private function validatePayload()
    {
        if (empty($this->pixKey)) {
            throw InvalidPixKeyException::keyCantBeEmpty();
        }

        if (empty($this->merchantName)) {
            throw InvalidMerchantInformationException::merchantNameCantBeEmpty();
        }

        if (empty($this->merchantCity)) {
            throw InvalidMerchantInformationException::merchantCityCantBeEmpty();
        }

        if (empty($this->amount)) {
            throw InvalidAmountException::amountCantBeEmpty();
        }
    }

    protected function getPixKey(): string
    {
        return $this->pixKey;
    }
}