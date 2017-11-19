<?php
declare(strict_types=1);

namespace ReadModel\Tests\Filters\DataTransformer;

use ReadModel\Filters\DataTransformer\ArrayTransformer;
use PHPUnit\Framework\TestCase;

class ArrayTransformerTest extends TestCase
{
    /** @var ArrayTransformer */
    private $transformer;

    protected function setup()
    {
        $this->transformer = new ArrayTransformer();
    }

    /**
     * @test
     */
    public function should_transform_to_array()
    {
        $result = $this->transformer->transform('value');
        $this->assertEquals(['value'], $result);

        $result = $this->transformer->transform(['value']);
        $this->assertEquals(['value'], $result);
    }

    /**
     * @test
     */
    public function should_simplify_array_to_comma_separated_list()
    {
        $result = $this->transformer->simplify('value');
        $this->assertEquals('value', $result);

        $result = $this->transformer->simplify(['one', 'two']);
        $this->assertEquals('one,two', $result);
    }
}
