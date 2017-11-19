<?php
declare(strict_types=1);

namespace ReadModel\Tests\Walker;

use ReadModel\Walker\ScalarTransformerWalker;
use PHPUnit\Framework\TestCase;

class ScalarTransformerWalkerTest extends TestCase
{
    /**
     * @test
     */
    public function should_transform_strings_to_scalars()
    {
        $walker = new ScalarTransformerWalker([
            'id' => 'int',
            'price' => 'float',
            'active' => 'bool'
        ]);

        $this->assertSame(
            ['id' => 5, 'price' => 29.99, 'active' => true],
            $walker->walk(['id' => '5', 'price' => '29.99', 'active' => '1'])
        );
    }

    /**
     * @test
     */
    public function should_not_transform_null_values()
    {
        $walker = new ScalarTransformerWalker([
            'id' => 'int'
        ]);

        $this->assertSame(
            ['id' => null, 'name' => 'name'],
            $walker->walk(['id' => null, 'name' => 'name'])
        );
    }

    /**
     * @test
     * @expectedException \ReadModel\InvalidArgumentException
     */
    public function should_prevent_from_transforming_to_unknown_type()
    {
        $walker = new ScalarTransformerWalker([
            'price' => 'unknown',
        ]);

        $walker->walk(['price' => '29.99']);
    }
}
