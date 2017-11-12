<?php
declare(strict_types=1);

namespace ReadModel\Filters\DataTransformer;

interface DataTransformer
{
    /**
     * Transform a value.
     *
     * @param $value
     * @return mixed
     *
     * // @todo używane w Filters::useFilter() (czyli pewnie tylko w Query :) )
     */
    public function transform($value);

    /**
     * Simplify a value.
     *
     * @param $value
     * @return mixed
     *
     * // @todo używane w Filters::addMetaToPaginator() (przy budowaniu mety dla query)
     */
    public function simplify($value);
}
