<?php
declare(strict_types=1);

namespace ReadModel\Filters\DataTransformer;

class FloatTransformer implements DataTransformer
{
    public function transform($value)
    {
        return (float) $value;
    }

    public function simplify($value)
    {
        return $this->transform($value);
    }
}
