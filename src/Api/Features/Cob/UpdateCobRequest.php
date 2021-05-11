<?php

namespace Junges\Pix\Api\Features\Cob;

use Junges\Pix\Api\Contracts\GeneratesCobRequests;

class UpdateCobRequest implements GeneratesCobRequests
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
        $request = [];

        if (!empty($this->amount)) {
            $request['valor'] = [
                'original' => $this->amount,
            ];
        }

        if (!empty($this->payingRequest)) {
            $request['solicitacaoPagador'] = $this->payingRequest;
        }

        if (!empty($this->debtorName)) {
            $request['devedor'] = [
                'nome' => $this->debtorName,
            ];
        }

        if (!empty($this->loc)) {
            $request['loc'] = [
                'id' => $this->loc,
            ];
        }

        if (!empty($this->debtorCpf)) {
            $request['devedor']['cpf'] = $this->debtorCpf;
        }

        if (!empty($this->debtorCnpj)) {
            $request['devedor']['cnpj'] = $this->debtorCnpj;
        }

        return $request;
    }

    public function transactionId(string $transactionId): UpdateCobRequest
    {
        $this->transactionId = $transactionId;

        return $this;
    }

    public function loc(string $loc): UpdateCobRequest
    {
        $this->loc = $loc;

        return $this;
    }

    public function debtorName(string $debtorName): UpdateCobRequest
    {
        $this->debtorName = $debtorName;

        return $this;
    }

    public function debtorCpf(string $debtorCpf): UpdateCobRequest
    {
        $this->debtorCpf = $debtorCpf;

        return $this;
    }

    public function debtorCnpj(string $debtorCnpj): UpdateCobRequest
    {
        $this->debtorCnpj = $debtorCnpj;

        return $this;
    }

    public function amount(string $amount): UpdateCobRequest
    {
        $this->amount = $amount;

        return $this;
    }

    public function payingRequest(string $payingRequest): UpdateCobRequest
    {
        $this->payingRequest = $payingRequest;

        return $this;
    }
}