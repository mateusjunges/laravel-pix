<?php

namespace Junges\Pix\Tests\Api\Filters;

use Junges\Pix\Api\Filters\CobFilters;
use Junges\Pix\Exceptions\ValidationException;
use Junges\Pix\Tests\TestCase;

class CobFilterTest extends TestCase
{
    public function test_it_return_filters_in_the_correct_format()
    {
        $expected = [
            'inicio' => $start = now()->subMonth()->toISOString(),
            'fim' => $end = now()->subMonth()->toISOString(),
        ];

        $filters = (new CobFilters())
            ->startingAt($start)
            ->endingAt($end);

        $this->assertSame($expected, $filters->toArray());
    }

    public function test_it_throws_exception_if_start_or_end_are_empty()
    {
        $this->expectException(ValidationException::class);

        $filters = (new CobFilters())->toArray();
    }
}