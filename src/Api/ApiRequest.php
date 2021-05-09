<?php

namespace Junges\Pix\Api;

use Junges\Pix\Exceptions\InvalidAmountException;
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

    public function debtorCpf(string $cpf): ApiRequest
    {
        $this->debtor['cpf'] = $cpf;

        return $this;
    }

    public function debtorCnpj(string $cnpj): ApiRequest
    {
        $this->debtor['cnpj'] = $cnpj;

        return $this;
    }

    public function debtorName(string $name): ApiRequest
    {
        $this->debtor['nome'] = $name;

        return $this;
    }

    /**
     * @throws \Junges\Pix\Exceptions\PixException
     */
    public function amount(string $amount): ApiRequest
    {
        if (! preg_match("^[0-9]{1,10}.[0-9]{2}$^", $amount)) {
            throw InvalidAmountException::invalidPattern();
        }

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
        $array = [
            "calendario" => [
                "expiracao" => $this->expiration
            ],
            "devedor" => $this->debtor,
            "valor" => [
                "original" => $this->amount,
            ],
            "chave" => $this->pixKey,
            "solicitacaoPagador" => $this->payingRequest,
        ];

        if ($this->additionalInfo ?? false) {
            $array['infoAdicional'] = $this->additionalInfo;
        }

        return $array;
    }
}