<?php
declare(strict_types=1);

namespace ReadModel\Tests\Filters;

use ReadModel\Filters\DataTransformer\DataTransformer;
use ReadModel\Filters\Filter;
use PHPUnit\Framework\TestCase;

class FilterTest extends TestCase
{
    /**
     * @test
     */
    public function should_trim_value_and_delegate_transformation()
    {
        $transformer = $this->prophesize(DataTransformer::class);
        $filter = new Filter('filter', ' value ', $transformer->reveal());

        $filter->simplifiedValue();
        $filter->transformedValue();

        $transformer->simplify('value')->shouldHaveBeenCalled();
        $transformer->transform('value')->shouldHaveBeenCalled();
    }

    /**
     * @test
     */
    public function should_not_delegate_transformation_for_null_values()
    {
        $transformer = $this->prophesize(DataTransformer::class);
        $filter = new Filter('filter', null, $transformer->reveal());

        $filter->simplifiedValue();
        $filter->transformedValue();

        $transformer->simplify(null)->shouldNotHaveBeenCalled();
        $transformer->transform(null)->shouldNotHaveBeenCalled();
    }

    /**
     * @test
     */
    public function should_trim_array_values()
    {
        $transformer = $this->prophesize(DataTransformer::class);
        $filter = new Filter('filter', [' test '], $transformer->reveal());

        $filter->simplifiedValue();

        $transformer->simplify(['test'])->shouldHaveBeenCalled();
    }
}
