<?php
declare(strict_types=1);

namespace ReadModel\Tests\Walker;

use ReadModel\Walker\BinaryUuidTransformer;
use ReadModel\Walker\BinaryUuidTransformerWalker;
use PHPUnit\Framework\TestCase;

class BinaryUuidTransformerWalkerTest extends TestCase
{
    /**
     * @test
     */
    public function should_transform_binary_values_to_strings()
    {
        $transformer = $this->prophesize(BinaryUuidTransformer::class);
        $walker = new BinaryUuidTransformerWalker($transformer->reveal(), 'id', 'parent_id', 'category_id');

        $transformer->transformToString('id')->shouldBeCalledTimes(1);
        $transformer->transformToString('parent_id')->shouldBeCalledTimes(1);

        $walker->walk(['id'=>'id', 'name'=>'name', 'parent_id'=>'parent_id', 'desc'=>null, 'category_id'=>null]);
    }
}
