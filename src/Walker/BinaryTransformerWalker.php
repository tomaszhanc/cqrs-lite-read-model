<?php
declare(strict_types=1);

namespace ReadModel\Walker;

class BinaryTransformerWalker implements ResultWalker
{
    /** @var BinaryTransformer */
    private $transformer;

    /** @var array */
    private $keys;

    public function __construct(BinaryTransformer $transformer, string ...$keys)
    {
        $this->transformer = $transformer;
        $this->keys = array_combine($keys, $keys);
    }

    public function walk(array $result): array
    {
        array_walk($result, function (&$value, $key) {
            if ($value === null || !isset($this->keys[$key])) {
                return;
            }

            $value = $this->transformer->transformToString($value);
        });

        return $result;
    }
}
