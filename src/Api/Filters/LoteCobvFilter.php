<?php

namespace Junges\Pix\Api\Filters;

use Junges\Pix\Api\Contracts\ApplyApiFilters;

class LoteCobvFilter implements ApplyApiFilters
{
    const PAGINATION_ACTUAL_PAGE = "paginacao.paginaAtual";
    const START = 'inicio';
    const END = 'fim';
    const PAGINATION_ITEMS_PER_PAGE = 'paginacao.itensPorPagina';

    private string $start;
    private string $end;
    private int $actualPage;
    private int $itemsPerPage;

    public function startingAt(string $start): LoteCobvFilter
    {
        $this->start = $start;
        return $this;
    }

    public function endingAt(string $end): LoteCobvFilter
    {
        $this->end = $end;
        return $this;
    }

    public function actualPage(int $actualPage): LoteCobvFilter
    {
        $this->actualPage = $actualPage;
        return $this;
    }

    public function itemsPerPage(int $itemsPerPage): LoteCobvFilter
    {
        $this->itemsPerPage = $itemsPerPage;
        return $this;
    }

    public function toArray(): array
    {
        $filters = [
            self::START => $this->start,
            self::END => $this->end,
        ];

        if (!empty($this->actualPage)) {
            $filters[self::PAGINATION_ACTUAL_PAGE] = $this->actualPage;
        }

        if (!empty($this->itemsPerPage)) {
            $filters[self::PAGINATION_ITEMS_PER_PAGE] = $this->itemsPerPage;
        }

        return $filters;
    }
}