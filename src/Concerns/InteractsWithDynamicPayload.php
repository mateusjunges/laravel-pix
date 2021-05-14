<?php

namespace Junges\Pix\Concerns;

use Junges\Pix\Exceptions\InvalidMerchantInformationException;
use Junges\Pix\Exceptions\InvalidTransactionIdException;
use Junges\Pix\Pix;

trait InteractsWithDynamicPayload
{
    use FormatPayloadValues;
    use HasCR16;

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

        $url = $this->formatValue(
            Pix::MERCHANT_ACCOUNT_INFORMATION_URL,
            preg_replace('/^https?:\/\//', '', $this->url)
        );

        return $this->formatValue(Pix::MERCHANT_ACCOUNT_INFORMATION, $gui, $url);
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

    protected function getPointOfInitializationMethod(): string
    {
        return $this->reusable ?? false
            ? ''
            : $this->formatValue(Pix::POINT_OF_INITIATION_METHOD, '12');
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
            .$this->getPointOfInitializationMethod()
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
}
