<?php
declare(strict_types=1);

namespace ReadModel\Tests\Filters\DataTransformer;

use ReadModel\Filters\DataTransformer\NullTransformer;
use PHPUnit\Framework\TestCase;

class NullTransformerTest extends TestCase
{
    const ANYTHING = 'anything';

    /** @var NullTransformer */
    private $transformer;

    protected function setup()
    {
        $this->transformer = new NullTransformer();
    }

    /**
     * @test
     */
    public function should_not_transform_anything()
    {
        $result = $this->transformer->transform(self::ANYTHING);
        $this->assertEquals(self::ANYTHING, $result);

        $result = $this->transformer->simplify(self::ANYTHING);
        $this->assertEquals(self::ANYTHING, $result);
    }
}
