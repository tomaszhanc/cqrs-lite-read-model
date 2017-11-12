<?php
declare(strict_types=1);

namespace ReadModel\Tests\Walker;

use ReadModel\Walker\CallableWalker;
use PHPUnit\Framework\TestCase;

class CallableWalkerTest extends TestCase
{
    /**
     * @test
     */
    public function should_transform_results_through_callable()
    {
        $walker = new CallableWalker(function (array $result) {
            $result = array_map('strtoupper', $result);
            array_shift($result);
            $result[] = 'D';

            return $result;
        });

        $this->assertSame(['B', 'C', 'D'], $walker->walk(['a', 'b', 'c']));
    }
}
