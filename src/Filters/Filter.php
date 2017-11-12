<?php
declare(strict_types=1);

namespace ReadModel\Filters;

use ReadModel\Filters\DataTransformer\DataTransformer;
use ReadModel\Filters\DataTransformer\NullTransformer;

final class Filter
{
    /** @var string */
    private $name;

    /** @var mixed */
    private $value;

    /** @var DataTransformer */
    private $transformer;

    public function __construct(string $name, $value, DataTransformer $transformer = null)
    {
        $this->name = $name;
        $this->value = $this->trim($value);
        $this->transformer = $transformer ?? new NullTransformer();
    }

    public function simplifiedValue()
    {
        if ($this->value === null) {
            return null;
        }

        return $this->transformer->simplify($this->value);
    }

    public function transformedValue()
    {
        if ($this->value === null) {
            return null;
        }

        return $this->transformer->transform($this->value);
    }

    public function name(): string
    {
        return $this->name;
    }

    private function trim($value)
    {
        if (is_string($value)) {
            return trim($value);
        }

        if (is_array($value)) {
            return array_map(function ($v) { return trim($v); }, $value);
        }

        return $value;
    }
}
