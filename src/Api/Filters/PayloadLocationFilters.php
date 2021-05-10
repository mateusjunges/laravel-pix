<?php

namespace Junges\Pix\Api\Filters;

use Junges\Pix\Api\Contracts\ApplyApiFilters;

class PayloadLocationFilters implements ApplyApiFilters
{
    const START = 'inicio';
    const END = 'fim';
    const TXID_PRESENT = "txIdPresente";
    const COB_TYPE = 'tipoCob';
    const PAGINATION_ACTUAL_PAGE = 'paginacao.paginaAtual';
    const PAGINATION_ITEMS_PER_PAGE = 'paginacao.itensPorPagina';

    private string $start;
    private string $end;
    private string $transactionIdPresent;
    private string $cobType;
    private string $actualPage;
    private string $itemsPerPage;

    public function startingAt(string $start): PayloadLocationFilters
    {
        $this->start = $start;
        return $this;
    }

    public function endingAt(string $end): PayloadLocationFilters
    {
        $this->end = $end;
        return $this;
    }

    public function withTransactionIdPresent(): PayloadLocationFilters
    {
        $this->transactionIdPresent = "true";
        return $this;
    }

    public function withoutTransactionIdPresent(): PayloadLocationFilters
    {
        $this->transactionIdPresent = "false";
        return $this;
    }

    public function withTypeCob(): PayloadLocationFilters
    {
        $this->cobType = "cob";
        return $this;
    }

    public function withTypeCobv(): PayloadLocationFilters
    {
        $this->cobType = "cobv";
        return $this;
    }

    public function actualPage(string $actualPage): PayloadLocationFilters
    {
        $this->actualPage = $actualPage;
        return $this;
    }

    public function itemsPerPage(string $itemsPerPage): PayloadLocationFilters
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

        if (!empty($this->transactionIdPresent)) {
            $filters[self::TXID_PRESENT] = $this->transactionIdPresent;
        }

        if (!empty($this->cobType)) {
            $filters[self::COB_TYPE] = $this->cobType;
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