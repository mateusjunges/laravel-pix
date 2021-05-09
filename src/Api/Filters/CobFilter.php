<?php

namespace Junges\Pix\Api\Filters;

use Junges\Pix\Api\Contracts\FilterApiRequests;

class CobFilter implements FilterApiRequests
{
    const START = 'inicio';
    const END = 'fim';
    const CPF = 'cpf';
    const CNPJ = 'cnpj';
    const LOCATION_PRESENT = 'locationPresente';
    const STATUS = 'status';
    const PAGINATION = 'paginacao';
    const ITEMS_PER_PAGE = 'itensPorPagina';
    const ACTUAL_PAGE = 'paginaAtual';

    private string $start;
    private string $end;
    private string $cpf;
    private string $cnpj;
    private string $locationPresent = "false";
    private string $status;
    private int $itemsPerPage;
    private int $actualPage;

    public function startingAt(string $start): CobFilter
    {
        $this->start = $start;

        return $this;
    }

    public function endingAt(string $end): CobFilter
    {
        $this->end = $end;

        return $this;
    }

    public function cpf(string $cpf): CobFilter
    {
        $this->cpf = $cpf;

        return $this;
    }

    public function cnpj(string $cnpj): CobFilter
    {
        $this->cnpj = $cnpj;
        return $this;
    }

    public function withLocationPresent(): CobFilter
    {
        $this->locationPresent = "true";
        return $this;
    }

    public function withoutLocationPresent(): CobFilter
    {
        $this->locationPresent = "false";

        return $this;
    }

    public function withStatus(string $status): CobFilter
    {
        $this->status = $status;

        return $this;
    }

    public function itemsPerPage(int $itemsPerPage): CobFilter
    {
        $this->itemsPerPage = $itemsPerPage;

        return $this;
    }

    public function actualPage(int $actualPage): CobFilter
    {
        $this->actualPage = $actualPage;

        return $this;
    }

    public function toArray(): array
    {
        $filters = [
            self::START => $this->start,
            self::END => $this->end,
            self::LOCATION_PRESENT => $this->locationPresent,
        ];

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
            $filters[self::PAGINATION][self::ITEMS_PER_PAGE] = $this->itemsPerPage;
        }

        if (!empty($this->actualPage)) {
            $filters[self::PAGINATION][self::ACTUAL_PAGE] = $this->actualPage;
        }

        return $filters;
    }
}