<?php
declare(strict_types=1);

namespace ReadModel\Filters\DataTransformer;

class ArrayTransformer implements DataTransformer
{
    public function transform($value)
    {
        return (array) $value;
    }

    public function simplify($value)
    {
        return implode(',', $this->transform($value));
    }
}
