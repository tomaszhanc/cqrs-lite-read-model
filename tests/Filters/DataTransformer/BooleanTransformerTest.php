<?php
declare(strict_types=1);

namespace ReadModel\Tests\Filters\DataTransformer;

use ReadModel\Filters\DataTransformer\BooleanTransformer;
use PHPUnit\Framework\TestCase;

class BooleanTransformerTest extends TestCase
{
    /** @var BooleanTransformer */
    private $transformer;

    protected function setup()
    {
        $this->transformer = new BooleanTransformer();
    }

    /**
     * @test
     * @dataProvider getValues
     */
    public function should_transform_to_boolean($value, bool $expectedBoolean)
    {
        $result = $this->transformer->transform($value);
        $this->assertEquals($expectedBoolean, $result);

        $result = $this->transformer->simplify($value);
        $this->assertEquals($expectedBoolean, $result);
    }

    /**
     * @test
     * @expectedException \ReadModel\Filters\DataTransformer\InvalidValueException
     */
    public function should_throw_exception_for_not_booleanish_values()
    {
        $this->transformer->transform('not-boolean');
    }

    public function getValues()
    {
        yield [1, true];
        yield [true, true];
        yield ['true', true];
        yield ['1', true];
        yield [0, false];
        yield [false, false];
        yield ['false', false];
        yield ['0', false];
        yield ['', false];
    }
}
