<?php

namespace Junges\Pix\Api;

use Junges\Pix\Exceptions\InvalidDebtorException;

class ApiRequest
{
    private int $expiration = 3600;
    private array $debtor;
    private string $amount;
    private string $pixKey;
    private string $payingRequest;
    private array $additionalInfo;
    private string $transactionId;

    public function setExpiration(int $expiration): ApiRequest
    {
        $this->expiration = $expiration;

        return $this;
    }

    /**
     * @throws InvalidDebtorException When the $debtor array doesn't have a 'cpf' key.
     */
    public function debtorWithCpf(array $debtor): ApiRequest
    {
        if (! array_key_exists('cpf', $debtor)) {
            throw InvalidDebtorException::debtorWithCpfMustContainACpfKey();
        }

        $this->debtor = $debtor;

        return $this;
    }

    /**
     * @throws InvalidDebtorException When the $debtor array doesn't have a 'cnpj' key.
     */
    public function debtorWithCnpj(array $debtor): ApiRequest
    {
        if (! array_key_exists('cnpj', $debtor)) {
            throw InvalidDebtorException::debtorWithCnpjfMustContainACnpjKey();
        }

        $this->debtor = $debtor;

        return $this;
    }

    public function amount(string $amount): ApiRequest
    {
        $this->amount = $amount;

        return $this;
    }

    public function pixKey(string $pixKey): ApiRequest
    {
        $this->pixKey = $pixKey;

        return $this;
    }

    public function payingRequest(string $payingRequest): ApiRequest
    {
        $this->payingRequest = $payingRequest;

        return $this;
    }

    public function additionalInfo(array $additionalInfo): ApiRequest
    {
        $this->additionalInfo = array_merge($this->additionalInfo ?? [], $additionalInfo);

        return $this;
    }

    public function transactionId(string $transactionId): ApiRequest
    {
        $this->transactionId = $transactionId;

        return $this;
    }

    public function getTransactionId(): string
    {
        return $this->transactionId;
    }

    public function toArray(): array
    {
        return [
            "calendario" => [
                "expiracao" => $this->expiration
            ],
            "devedor" => $this->debtor,
            "valor" => [
                "original" => $this->amount,
            ],
            "chave" => $this->pixKey,
            "solicitacaoPagador" => $this->payingRequest,
            "infoAdicionais" => $this->additionalInfo
        ];
    }
}