<?php

namespace Junges\Pix\Api\Filters;

use Junges\Pix\Api\Contracts\ApplyApiFilters;
use Junges\Pix\Exceptions\ValidationException;

class LoteCobvFilter implements ApplyApiFilters
{
    const PAGINATION_CURRENT_PAGE = 'paginacao.paginaAtual';
    const START = 'inicio';
    const END = 'fim';
    const PAGINATION_ITEMS_PER_PAGE = 'paginacao.itensPorPagina';

    private string $start;
    private string $end;
    private int $currentPage;
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

    public function currentPage(int $currentPage): LoteCobvFilter
    {
        $this->currentPage = $currentPage;

        return $this;
    }

    public function itemsPerPage(int $itemsPerPage): LoteCobvFilter
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

        if (!empty($this->currentPage)) {
            $filters[self::PAGINATION_CURRENT_PAGE] = $this->currentPage;
        }

        if (!empty($this->itemsPerPage)) {
            $filters[self::PAGINATION_ITEMS_PER_PAGE] = $this->itemsPerPage;
        }

        return $filters;
    }
}
