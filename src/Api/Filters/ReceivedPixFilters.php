<?php

namespace Junges\Pix\Api\Filters;

use Junges\Pix\Api\Contracts\ApplyApiFilters;
use Junges\Pix\Api\Features\ReceivedPix\ReceivedPix;

class ReceivedPixFilters implements ApplyApiFilters
{
    const START = "inicio";
    const END = "fim";
    const TXID = "txid";
    const TXID_PRESENT = "txIdPresente";
    const REFUND_PRESENT = "devolucaoPresente";
    const CPF = "cpf";
    const CNPJ = "cnpj";
    const PAGINATION_ACTUAL_PAGE = "paginacao.paginaAtual";
    const PAGINATION_ITEMS_PER_PAGE = "paginacao.itensPorPagina";

    private string $start;
    private string $end;
    private string $transactionId;
    private string $transactionIdPresent;
    private string $refundPresent;
    private string $cpf;
    private string $cnpj;
    private string $actualPage;
    private string $itemsPerPage;

    public function startingAt(string $start): ReceivedPixFilters
    {
        $this->start = $start;
        return $this;
    }

    public function endingAt(string $end): ReceivedPixFilters
    {
        $this->end = $end;
        return $this;
    }

    public function transactionId(string $transactionId): ReceivedPixFilters
    {
        $this->transactionId = $transactionId;
        return $this;
    }

    public function withTransactionIdPresent(): ReceivedPixFilters
    {
        $this->transactionIdPresent = "true";
        return $this;
    }

    public function withoutTransactionIdPresent(): ReceivedPixFilters
    {
        $this->transactionIdPresent = "false";
        return $this;
    }


    public function withRefundPresent(): ReceivedPixFilters
    {
        $this->refundPresent = "true";
        return $this;
    }

    public function withoutRefundPresent(): ReceivedPixFilters
    {
        $this->refundPresent = "false";
        return $this;
    }

    public function cpf(string $cpf): ReceivedPixFilters
    {
        $this->cpf = $cpf;
        return $this;
    }

    public function cnpj(string $cnpj): ReceivedPixFilters
    {
        $this->cnpj = $cnpj;
        return $this;
    }

    public function actualPage(string $actualPage): ReceivedPixFilters
    {
        $this->actualPage = $actualPage;
        return $this;
    }

    public function itemsPerPage(string $itemsPerPage): ReceivedPixFilters
    {
        $this->itemsPerPage = $itemsPerPage;
        return $this;
    }

    public function toArray(): array
    {
        $filters = [
            self::START => $this->start,
            self::END => $this->end
        ];

        if (!empty($this->transactionId)) {
            $filters[self::TXID] = $this->transactionId;
        }

        if (!empty($this->transactionIdPresent)) {
            $filters[self::TXID_PRESENT] = $this->transactionIdPresent;
        }

        if (!empty($this->refundPresent)) {
            $filters[self::REFUND_PRESENT] = $this->refundPresent;
        }

        if (!empty($this->cpf)) {
            $filters[self::CPF] = $this->cpf;
        }

        if (!empty($this->cnpj)) {
            $filters[self::CNPJ] = $this->cnpj;
        }

        if (!empty($this->actualPage)) {
            $filters[self::PAGINATION_ACTUAL_PAGE] = $this->actualPage;
        }

        if (!empty($this->itemsPerPage)) {
            $filters[self::PAGINATION_ITEMS_PER_PAGE] = $this->itemsPerPage;
        }

        return $filters;
    }
}