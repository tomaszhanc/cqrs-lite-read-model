<?php
declare(strict_types=1);

namespace ReadModel\Tests;

use Prophecy\Argument;
use ReadModel\Tests\Fixtures\TestWalkablePaginator;
use ReadModel\WalkablePaginator;
use PHPUnit\Framework\TestCase;
use ReadModel\Walker\ResultWalker;

class WalkablePaginatorTest extends TestCase
{
    /** @var ResultWalker */
    private $walker;

    /** @var WalkablePaginator */
    private $paginator;

    protected function setUp()
    {
        $this->walker = $this->prophesize(ResultWalker::class);
        $this->paginator = new TestWalkablePaginator(
            100, 10,
            [['one', 'two'], ['three', 'four']],
            $this->walker->reveal()
        );
    }

    /**
     * @test
     */
    public function should_walk_through_results()
    {
        $this->walker->walk(Argument::type('array'))->willReturn(['a', 'b']);
        $results = $this->paginator->getResults();

        $this->assertEquals([['a', 'b'], ['a', 'b']], $results);
    }

    /**
     * @test
     */
    public function should_return_untouched_results_due_to_default_null_walker()
    {
        $paginator = new TestWalkablePaginator(100, 10, [['one', 'two'], ['three', 'four']]);
        $results = $paginator->getResults();

        $this->assertEquals([['one', 'two'], ['three', 'four']], $results);
    }
}
