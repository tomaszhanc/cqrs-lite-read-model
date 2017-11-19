<?php
declare(strict_types=1);

namespace ReadModel\Tests\Filters\DataTransformer;

use ReadModel\Filters\DataTransformer\DateTimeTransformer;
use PHPUnit\Framework\TestCase;

class DateTimeTransformerTest extends TestCase
{
    const DATE = '2017-11-19 01:01';

    /** @var DateTimeTransformer */
    private $transformer;

    protected function setup()
    {
        $this->transformer = new DateTimeTransformer();
    }

    /**
     * @test
     */
    public function should_transform_date_to_object()
    {
        $result = $this->transformer->transform(self::DATE);
        $this->assertEquals(new \DateTimeImmutable(self::DATE), $result, null, 1);
    }

    /**
     * @test
     */
    public function should_simplify_date_to_string()
    {
        $result = $this->transformer->simplify(self::DATE);
        $this->assertEquals(self::DATE, $result);
    }

    /**
     * @test
     */
    public function should_not_transform_empty_string()
    {
        $result = $this->transformer->simplify('');
        $this->assertEquals('', $result);
    }

    /**
     * @test
     * @expectedException \ReadModel\Filters\DataTransformer\InvalidValueException
     */
    public function should_prevent_working_with_invalid_dates()
    {
        $this->transformer->transform('not-a-date');
    }
}
