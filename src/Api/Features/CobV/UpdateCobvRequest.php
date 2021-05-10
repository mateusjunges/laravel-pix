<?php

namespace Junges\Pix\Api\Features\CobV;

use Junges\Pix\Api\Contracts\GeneratesCobvRequests;

class UpdateCobvRequest implements GeneratesCobvRequests
{
    private string $transactionId;
    private string $loc;
    private string $debtorName;
    private string $debtorCpf;
    private string $debtorCnpj;
    private string $amount;
    private string $payingRequest;

    public function getTransactionId(): string
    {
        return $this->transactionId;
    }

    public function toArray(): array
    {
        $request = [
            'loc' => [
                'id' => $this->loc,
            ],
            'devedor' => [
                'nome' => $this->debtorName,
            ],
            'valor' => [
                'original' => $this->amount,
            ],
            'solicitacaoPagador' => $this->payingRequest
        ];

        if (!empty($this->debtorCpf)) {
            $request['devedor']['cpf'] = $this->debtorCpf;
        }

        if (!empty($this->debtorCnpj)) {
            $request['devedor']['cnpj'] = $this->debtorCnpj;
        }

        return $request;
    }

    public function transactionId(string $transactionId): UpdateCobvRequest
    {
        $this->transactionId = $transactionId;

        return $this;
    }

    public function loc(string $loc): updateCobvRequest
    {
        $this->loc = $loc;

        return $this;
    }

    public function debtorName(string $debtorName): updateCobvRequest
    {
        $this->debtorName = $debtorName;

        return $this;
    }

    public function debtorCpf(string $debtorCpf): updateCobvRequest
    {
        $this->debtorCpf = $debtorCpf;

        return $this;
    }

    public function debtorCnpj(string $debtorCnpj): updateCobvRequest
    {
        $this->debtorCnpj = $debtorCnpj;

        return $this;
    }

    public function amount(string $amount): updateCobvRequest
    {
        $this->amount = $amount;

        return $this;
    }

    public function payingRequest(string $payingRequest): updateCobvRequest
    {
        $this->payingRequest = $payingRequest;

        return $this;
    }
}