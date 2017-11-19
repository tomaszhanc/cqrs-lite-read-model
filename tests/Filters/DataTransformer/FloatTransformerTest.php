<?php
declare(strict_types=1);

namespace ReadModel\Tests\Filters\DataTransformer;

use ReadModel\Filters\DataTransformer\FloatTransformer;
use PHPUnit\Framework\TestCase;

class FloatTransformerTest extends TestCase
{
    /** @var FloatTransformer */
    private $transformer;

    protected function setup()
    {
        $this->transformer = new FloatTransformer();
    }

    /**
     * @test
     * @dataProvider getValues
     */
    public function should_transform_to_float($value, bool $expected)
    {
        $result = $this->transformer->transform($value);
        $this->assertEquals($expected, $result);

        $result = $this->transformer->simplify($value);
        $this->assertEquals($expected, $result);
    }

    public function getValues()
    {
        yield [1, 1];
        yield ['1.2', 1.2];
        yield [-2, -2];
        yield ['-2.1', -2.1];
        yield ['not-float', 0];
    }
}
