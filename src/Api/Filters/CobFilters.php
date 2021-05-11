<?php

namespace Junges\Pix\Api\Filters;

use Junges\Pix\Api\Contracts\ApplyApiFilters;

class CobFilters implements ApplyApiFilters
{
    const START = 'inicio';
    const END = 'fim';
    const CPF = 'cpf';
    const CNPJ = 'cnpj';
    const LOCATION_PRESENT = 'locationPresente';
    const STATUS = 'status';
    const PAGINATION_ITEMS_PER_PAGE = 'paginacao.itensPorPagina';
    const PAGINATION_ACTUAL_PAGE = 'paginacao.paginaAtual';

    private string $start;
    private string $end;
    private string $cpf;
    private string $cnpj;
    private string $locationPresent;
    private string $status;
    private int $itemsPerPage;
    private int $actualPage;

    public function startingAt(string $start): CobFilters
    {
        $this->start = $start;

        return $this;
    }

    public function endingAt(string $end): CobFilters
    {
        $this->end = $end;

        return $this;
    }

    public function cpf(string $cpf): CobFilters
    {
        $this->cpf = $cpf;

        return $this;
    }

    public function cnpj(string $cnpj): CobFilters
    {
        $this->cnpj = $cnpj;
        return $this;
    }

    public function withLocationPresent(): CobFilters
    {
        $this->locationPresent = "true";
        return $this;
    }

    public function withoutLocationPresent(): CobFilters
    {
        $this->locationPresent = "false";

        return $this;
    }

    public function withStatus(string $status): CobFilters
    {
        $this->status = $status;

        return $this;
    }

    public function itemsPerPage(int $itemsPerPage): CobFilters
    {
        $this->itemsPerPage = $itemsPerPage;

        return $this;
    }

    public function actualPage(int $actualPage): CobFilters
    {
        $this->actualPage = $actualPage;

        return $this;
    }

    public function toArray(): array
    {
        $filters = [
            self::START => $this->start,
            self::END => $this->end,
        ];

        if (!empty($this->locationPresent)) {
            $filters[self::LOCATION_PRESENT] = $this->locationPresent;
        }

        if (!empty($this->cpf)) {
            $filters[self::CPF] = $this->cpf;
        }

        if (!empty($this->cnpj)) {
            $filters[self::CNPJ] = $this->cnpj;
        }

        if (!empty($this->status)) {
            $filters[self::STATUS] = $this->status;
        }

        if (!empty($this->itemsPerPage)) {
            $filters[self::PAGINATION_ITEMS_PER_PAGE] = $this->itemsPerPage;
        }

        if (!empty($this->actualPage)) {
            $filters[self::PAGINATION_ACTUAL_PAGE] = $this->actualPage;
        }

        return $filters;
    }
}