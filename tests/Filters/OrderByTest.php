<?php
declare(strict_types=1);

namespace ReadModel\Tests\Filters;

use ReadModel\Filters\OrderBy;
use PHPUnit\Framework\TestCase;

class OrderByTest extends TestCase
{
    /**
     * @test
     */
    public function should_create_ascending_order_by()
    {
        $orderBy = new OrderBy('name');

        $this->assertSame('name', $orderBy->field());
        $this->assertSame('asc', $orderBy->order());
        $this->assertSame('name', (string) $orderBy);
    }

    /**
     * @test
     */
    public function should_create_descending_order_by()
    {
        $orderBy = new OrderBy('-name');

        $this->assertSame('name', $orderBy->field());
        $this->assertSame('desc', $orderBy->order());
        $this->assertSame('-name', (string) $orderBy);
    }
}
