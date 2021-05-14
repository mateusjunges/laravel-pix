<?php

namespace Junges\Pix\Api\Filters;

use Junges\Pix\Api\Contracts\ApplyApiFilters;
use Junges\Pix\Exceptions\ValidationException;

class PayloadLocationFilters implements ApplyApiFilters
{
    const START = 'inicio';
    const END = 'fim';
    const TXID_PRESENT = 'txIdPresente';
    const COB_TYPE = 'tipoCob';
    const PAGINATION_CURRENT_PAGE = 'paginacao.paginaAtual';
    const PAGINATION_ITEMS_PER_PAGE = 'paginacao.itensPorPagina';

    private string $start;
    private string $end;
    private string $transactionIdPresent;
    private string $cobType;
    private string $currentPage;
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
        $this->transactionIdPresent = 'true';

        return $this;
    }

    public function withoutTransactionIdPresent(): PayloadLocationFilters
    {
        $this->transactionIdPresent = 'false';

        return $this;
    }

    public function withTypeCob(): PayloadLocationFilters
    {
        $this->cobType = 'cob';

        return $this;
    }

    public function withTypeCobv(): PayloadLocationFilters
    {
        $this->cobType = 'cobv';

        return $this;
    }

    public function currentPage(string $currentPage): PayloadLocationFilters
    {
        $this->currentPage = $currentPage;

        return $this;
    }

    public function itemsPerPage(string $itemsPerPage): PayloadLocationFilters
    {
        $this->itemsPerPage = $itemsPerPage;

        return $this;
    }

    /**
     * @throws ValidationException
     *
     * @return array
     */
    public function toArray(): array
    {
        if (empty($this->start) || empty($this->end)) {
            throw ValidationException::invalidStartAndEndFields();
        }

        $filters = [
            self::START => $this->start,
            self::END   => $this->end,
        ];

        if (!empty($this->transactionIdPresent)) {
            $filters[self::TXID_PRESENT] = $this->transactionIdPresent;
        }

        if (!empty($this->cobType)) {
            $filters[self::COB_TYPE] = $this->cobType;
        }

        if (!empty($this->currentPage)) {
            $filters[self::PAGINATION_CURRENT_PAGE] = $this->currentPage;
        }

        if (!empty($this->itemsPerPage)) {
            $filters[self::PAGINATION_ITEMS_PER_PAGE] = $this->itemsPerPage;
        }

        return $filters;
    }
}
