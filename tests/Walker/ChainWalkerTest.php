<?php
declare(strict_types=1);

namespace ReadModel\Tests\Walker;

use ReadModel\Walker\CallableWalker;
use ReadModel\Walker\ChainWalker;
use PHPUnit\Framework\TestCase;
use ReadModel\Walker\ResultWalker;

class ChainWalkerTest extends TestCase
{
    /** @var ResultWalker */
    private $upperCaseWalker;

    /** @var ResultWalker */
    private $dummyWalker;

    /** @var ChainWalker */
    private $walker;

    protected function setUp()
    {
        $this->upperCaseWalker = new CallableWalker(function (array $result) {
            return array_map('strtoupper', $result);
        });
        $this->dummyWalker = $this->prophesize(ResultWalker::class);

        $this->walker = new ChainWalker(
            $this->upperCaseWalker,
            $this->dummyWalker->reveal()
        );
    }

    /**
     * @test
     */
    public function should_next_walker_should_get_result_from_previous_one()
    {
        $this->dummyWalker->walk(['ONE', 'TWO', 'THREE'])->shouldBeCalled()->willReturnArgument();
        $this->walker->walk(['one', 'two', 'three']);
    }
}
