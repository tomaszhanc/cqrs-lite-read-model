<?php
declare(strict_types=1);

namespace ReadModel\Filters\DataTransformer;

class NullTransformer implements DataTransformer
{
    public function transform($value)
    {
        return $value;
    }

    public function simplify($value)
    {
        return $this->transform($value);
    }
}
