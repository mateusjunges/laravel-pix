<?php

namespace Junges\Pix\Api\Features\LoteCobv;

use Illuminate\Contracts\Support\Arrayable;

class UpdateCobvItem implements Arrayable
{
    private string $dueDate;
    private string $transactionId;
    private string $amount;

    public function dueDate(string $dueDate): UpdateCobvItem
    {
        $this->dueDate = $dueDate;

        return $this;
    }

    public function setTransactionId(string $transactionId): UpdateCobvItem
    {
        $this->transactionId = $transactionId;

        return $this;
    }

    public function amount(string $amount): UpdateCobvItem
    {
        $this->amount = $amount;

        return $this;
    }

    public function toArray()
    {
        return [
            "calendario" => [
                "dataDeVencimento" => $this->dueDate
            ],
            "txid" => $this->transactionId,
            "valor" => [
                "original" => $this->amount
            ]
        ];
    }
}