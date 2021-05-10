<?php

namespace Junges\Pix\Api\Features\Cobv;

use Illuminate\Contracts\Support\Arrayable;

class CobvRequest implements Arrayable
{
    private string $transactionId;
    private string $dueDate;
    private int $validAfterDueDate;
    private int $loc;
    private string $debtorStreet;
    private string $debtorCity;
    private string $debtorUf;
    private string $debtorCpf;
    private string $debtorCnpj;
    private string $debtorCep;
    private string $debtorName;
    private string $fineModality = "1";
    private string $payingRequest;
    private string $finePercentageAmount;
    private string $feeModality = "2";
    private string $feePercentageValue = "2";
    private string $pixKey;
    private string $amount;
    private string $discountModality;
    private string $discountFixedDate;
    private string $discountFixedDatePercentageValue;

    public function toArray(): array
    {
        return [
            "calendario" => [
                "dataDeVencimento" => $this->dueDate,
                "validadeAposVencimento" => $this->validAfterDueDate
            ],
            "loc" => [
                "id" => $this->loc,
            ],
            "devedor" => [
                "logradouro" => $this->debtorStreet,
                "cidade" => $this->debtorCity,
                "uf" => $this->debtorUf,
                "cep" => $this->debtorCep,
                "cpf" => $this->debtorCpf,
                "nome" => $this->debtorName,
            ],
            "valor" => [
                "original" => $this->amount,
                "multa" => [
                    "modalidade" => $this->fineModality,
                    "valorPerc" => $this->finePercentageAmount,
                ],
                "juros" => [
                    "modalidade" => $this->feeModality,
                    "valorPerc" => $this->feePercentageValue
                ],
                "desconto" => [
                    "modalidade" => $this->discountModality,
                    "descontoDataFixa" => [
                        "data" => $this->discountFixedDate,
                        "valorPerc" => $this->discountFixedDatePercentageValue
                    ]
                ]
            ],
            "chave" => $this->pixKey,
            "solicitacaoPagador" => $this->payingRequest
        ];
    }

    public function dueDate(string $dueDate): CobvRequest
    {
        $this->dueDate = $dueDate;
        return $this;
    }

    public function validAfterDueDate(int $validAfterDueDate): CobvRequest
    {
        $this->validAfterDueDate = $validAfterDueDate;
        return $this;
    }

    public function loc(int $loc): CobvRequest
    {
        $this->loc = $loc;
        return $this;
    }

    public function debtorStreet(string $debtorStreet): CobvRequest
    {
        $this->debtorStreet = $debtorStreet;
        return $this;
    }

    public function debtorCity(string $debtorCity): CobvRequest
    {
        $this->debtorCity = $debtorCity;
        return $this;
    }

    public function debtorUf(string $debtorUf): CobvRequest
    {
        $this->debtorUf = $debtorUf;
        return $this;
    }

    public function debtorCpf(string $debtorCpf): CobvRequest
    {
        $this->debtorCpf = $debtorCpf;
        return $this;
    }

    public function debtorCnpj(string $debtorCnpj): CobvRequest
    {
        $this->debtorCnpj = $debtorCnpj;
        return $this;
    }

    public function debtorCep(string $debtorCep): CobvRequest
    {
        $this->debtorCep = $debtorCep;
        return $this;
    }

    public function debtorName(string $debtorName): CobvRequest
    {
        $this->debtorName = $debtorName;
        return $this;
    }

    public function fineModality(string $fineModality): CobvRequest
    {
        $this->fineModality = $fineModality;
        return $this;
    }

    public function payingRequest(string $payingRequest): CobvRequest
    {
        $this->payingRequest = $payingRequest;
        return $this;
    }

    public function finePercentageAmount(string $finePercentageAmount): CobvRequest
    {
        $this->finePercentageAmount = $finePercentageAmount;
        return $this;
    }

    public function feePercentageValue(string $feePercentageValue): CobvRequest
    {
        $this->feePercentageValue = $feePercentageValue;
        return $this;
    }

    public function pixKey(string $pixKey): CobvRequest
    {
        $this->pixKey = $pixKey;
        return $this;
    }

    public function amount(string $amount): CobvRequest
    {
        $this->amount = $amount;
        return $this;
    }

    public function discountModality(string $discountModality): CobvRequest
    {
        $this->discountModality = $discountModality;
        return $this;
    }

    public function discountFixedDate(string $discountFixedDate): CobvRequest
    {
        $this->discountFixedDate = $discountFixedDate;
        return $this;
    }

    public function discountFixedDatePercentageValue(string $discountFixedDatePercentageValue): CobvRequest
    {
        $this->discountFixedDatePercentageValue = $discountFixedDatePercentageValue;
        return $this;
    }

    public function transactionId(string $transactionId): CobvRequest
    {
        $this->transactionId = $transactionId;
        return $this;
    }

    public function getTransactionId(): string
    {
        return $this->transactionId;
    }
}