<?php

namespace Junges\Pix\Api\Filters;

use Junges\Pix\Api\Contracts\ApplyApiFilters;

class WebhookFilters implements ApplyApiFilters
{
    const START = 'inicio';
    const END = 'fim';
    const PAGINATION_ITEMS_PER_PAGE = 'paginacao.itensPorPagina';
    const PAGINATION_CURRENT_PAGE = 'paginacao.paginaAtual';

    private string $start;
    private string $end;
    private string $itemsPerPage;
    private string $currentPage;

    public function startingAt(string $start): WebhookFilters
    {
        $this->start = $start;

        return $this;
    }

    public function endingAt(string $end): WebhookFilters
    {
        $this->end = $end;

        return $this;
    }

    public function itemsPerPage(string $itemsPerPage): WebhookFilters
    {
        $this->itemsPerPage = $itemsPerPage;

        return $this;
    }

    public function currentPage(string $currentPage): WebhookFilters
    {
        $this->currentPage = $currentPage;

        return $this;
    }

    public function toArray(): array
    {
        $filters = [];

        if (!empty($this->start)) {
            $filters[self::START] = $this->start;
        }

        if (!empty($this->end)) {
            $filters[self::END] = $this->end;
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
