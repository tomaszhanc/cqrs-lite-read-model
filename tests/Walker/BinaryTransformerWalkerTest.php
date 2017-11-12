<?php
declare(strict_types=1);

namespace ReadModel\Tests\Walker;

use ReadModel\Walker\BinaryTransformer;
use ReadModel\Walker\BinaryTransformerWalker;
use PHPUnit\Framework\TestCase;

class BinaryTransformerWalkerTest extends TestCase
{

    /**
     * @test
     */
    public function should_transform_binary_values_to_strings()
    {
        $transformer = $this->prophesize(BinaryTransformer::class);
        $walker = new BinaryTransformerWalker($transformer->reveal(), ['id', 'parent_id', 'category_id']);

        $transformer->transformToString('id')->shouldBeCalledTimes(1);
        $transformer->transformToString('parent_id')->shouldBeCalledTimes(1);

        $walker->walk(['id'=>'id', 'name'=>'name', 'parent_id'=>'parent_id', 'desc'=>null, 'category_id'=>null]);
    }
}
