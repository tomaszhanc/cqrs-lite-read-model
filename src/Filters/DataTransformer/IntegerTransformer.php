<?php
declare(strict_types=1);

namespace ReadModel\Filters\DataTransformer;

class IntegerTransformer implements DataTransformer
{
    public function transform($value)
    {
        return intval($value);
    }

    public function simplify($value)
    {
        return $this->transform($value);
    }
}
