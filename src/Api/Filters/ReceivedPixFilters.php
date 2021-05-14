<?php

namespace Junges\Pix\Api\Filters;

use Junges\Pix\Api\Contracts\ApplyApiFilters;
use Junges\Pix\Exceptions\ValidationException;

class ReceivedPixFilters implements ApplyApiFilters
{
    const START = 'inicio';
    const END = 'fim';
    const TXID = 'txid';
    const TXID_PRESENT = 'txIdPresente';
    const REFUND_PRESENT = 'devolucaoPresente';
    const CPF = 'cpf';
    const CNPJ = 'cnpj';
    const PAGINATION_CURRENT_PAGE = 'paginacao.paginaAtual';
    const PAGINATION_ITEMS_PER_PAGE = 'paginacao.itensPorPagina';

    private string $start;
    private string $end;
    private string $transactionId;
    private string $transactionIdPresent;
    private string $refundPresent;
    private string $cpf;
    private string $cnpj;
    private string $currentPage;
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
        $this->transactionIdPresent = 'true';

        return $this;
    }

    public function withoutTransactionIdPresent(): ReceivedPixFilters
    {
        $this->transactionIdPresent = 'false';

        return $this;
    }

    public function withRefundPresent(): ReceivedPixFilters
    {
        $this->refundPresent = 'true';

        return $this;
    }

    public function withoutRefundPresent(): ReceivedPixFilters
    {
        $this->refundPresent = 'false';

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

    public function currentPage(string $currentPage): ReceivedPixFilters
    {
        $this->currentPage = $currentPage;

        return $this;
    }

    public function itemsPerPage(string $itemsPerPage): ReceivedPixFilters
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

        if (!empty($this->currentPage)) {
            $filters[self::PAGINATION_CURRENT_PAGE] = $this->currentPage;
        }

        if (!empty($this->itemsPerPage)) {
            $filters[self::PAGINATION_ITEMS_PER_PAGE] = $this->itemsPerPage;
        }

        return $filters;
    }
}
