<?php

namespace Junges\Pix\Tests\Api\Filters;

use Junges\Pix\Api\Filters\PayloadLocationFilters;
use Junges\Pix\Exceptions\ValidationException;
use Junges\Pix\Tests\TestCase;

class PayloadLocationFiltersTest extends TestCase
{
    public function test_it_return_filters_in_the_correct_format()
    {
        $expected = [
            'inicio'                   => $start = now()->subMonth()->toISOString(),
            'fim'                      => $end = now()->subMonth()->toISOString(),
            'txIdPresente'             => 'false',
            'paginacao.itensPorPagina' => 2,
            'paginacao.paginaAtual'    => 1,
        ];

        $filters = (new PayloadLocationFilters())
            ->startingAt($start)
            ->withoutTransactionIdPresent()
            ->currentPage(1)
            ->itemsPerPage(2)
            ->endingAt($end);

        $this->assertEquals($expected, $filters->toArray());
    }

    public function test_it_throws_exception_if_start_or_end_are_empty()
    {
        $this->expectException(ValidationException::class);

        (new PayloadLocationFilters())->toArray();
    }
}
