<?php

namespace Junges\Pix\Concerns;

use Illuminate\Support\Str;
use Junges\Pix\Exceptions\InvalidAmountException;
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

        $size =  Str::padLeft(
            Str::length($value),
            2,
            '0'
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

        $key = $this->key ?? false
                ? $this->formatValue(Pix::MERCHANT_ACCOUNT_INFORMATION_KEY, $this->pixKey)
                : "";

        $description = $this->description ?? false
                ? $this->formatValue(Pix::MERCHANT_ACCOUNT_INFORMATION_DESCRIPTION, $this->description)
                : "";

        $url = $this->url ?? false
                ? $this->formatValue(Pix::MERCHANT_ACCOUNT_INFORMATION_URL, $this->url)
                : "";

        return $this->formatValue(Pix::MERCHANT_ACCOUNT_INFORMATION, $gui, $key, $description, $url);
    }

    /**
     * @throws \Junges\Pix\Exceptions\PixException
     */
    protected function getTransactionAmount(): string
    {
        if (empty($this->amount)) {
            throw InvalidAmountException::amountCantBeEmpty();
        }

        return $this->formatValue(Pix::TRANSACTION_AMOUNT, $this->amount);
    }

    protected function getTransactionCurrency(): string
    {
        return $this->formatValue(Pix::TRANSACTION_CURRENCY, config('laravel-pix.currency_code', '986'));
    }

    protected function getPointOfInitializationMethod(): string
    {
        return $this->reusable ?? false
            ? $this->formatValue(Pix::POINT_OF_INITIATION_METHOD, '12')
            : '';
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

    /**
     * @throws \Junges\Pix\Exceptions\PixException
     */
    protected function buildPayload(): string
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

    public function getPixKey(): string
    {
        return $this->pixKey;
    }
}