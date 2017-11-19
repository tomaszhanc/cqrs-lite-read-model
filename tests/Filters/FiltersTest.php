<?php
declare(strict_types=1);

namespace ReadModel\Tests\Filters;

use ReadModel\Filters\Filter;
use ReadModel\Filters\Filters;
use PHPUnit\Framework\TestCase;
use ReadModel\Filters\OrderBy;
use ReadModel\Tests\Fixtures\TestPaginator;

class FiltersTest extends TestCase
{
    /** @var Filters */
    private $filters;

    protected function setup()
    {
        $this->filters = new Filters([new Filter('filter', 'value')], [new OrderBy('name')]);
    }

    /**
     * @test
     */
    public function should_use_filter()
    {
        $result = $this->filters->useFilter('filter');

        $this->assertEquals('value', $result);
        $this->assertEmpty($this->filters->unusedFilters()->current());
        $this->assertNotEmpty($this->filters->unusedOrdersBy());
    }

    /**
     * @test
     */
    public function should_use_order_by()
    {
        $result = $this->filters->useOrderBy('name');

        $this->assertInstanceOf(OrderBy::class, $result);
        $this->assertEmpty($this->filters->unusedOrdersBy()->current());
        $this->assertNotEmpty($this->filters->unusedFilters());
    }

    /**
     * @test
     */
    public function should_add_meta_to_paginator()
    {
        $paginator = new TestPaginator(null, 0, []);
        $this->filters->addMetaToPaginator($paginator);

        // tests postponed use of filter and order by
        $this->filters->useFilter('filter');
        $this->filters->useOrderBy('name');

        $this->assertEquals([
            'limit' => null,
            'offset' => 0,
            'total' => 0,
            'query' => ['filter' => 'value'],
            'order' => 'name'
        ], $paginator->getMeta());
    }
}
