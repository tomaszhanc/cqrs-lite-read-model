<?php
declare(strict_types=1);

namespace ReadModel\Filters\DataTransformer;

interface DataTransformer
{
    /**
     * Transform a value to compound value, eg: object or array.
     *
     * @param $value
     * @return mixed
     */
    public function transform($value);

    /**
     * Simplify a value, mostly to scalar or string.
     *
     * @param $value
     * @return mixed
     */
    public function simplify($value);
}
