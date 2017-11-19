<?php
declare(strict_types=1);

namespace ReadModel\Walker;

class EmbedWalker implements ResultWalker
{
    /** @var array */
    private $prefixes;

    /**
     * @param string[] $prefixes Prefixes to embed, eg: for 'address' prefix:
     *      [id => 5, address_street = 'Sesame', 'address_number' = 4]
     *      will be turned into:
     *      [id => 5, address => [street = 'Sesame', 'number' = 4]]
     */
    public function __construct(string ...$prefixes)
    {
        $this->prefixes = $prefixes;
    }

    public function walk(array $result): array
    {
        foreach ($this->prefixes as $prefix) {
            $result = $this->embed($prefix, $result);
        }

        return $result;
    }

    private function embed(string $prefix, array $result): array
    {
        // filter out prefixed fields
        $prefixed = array_filter($result, function ($key) use ($prefix) {
            return strpos($key, $prefix) === 0;
        }, ARRAY_FILTER_USE_KEY);

        // remove filtered out fields from result
        foreach (array_keys($prefixed) as $key) {
            unset($result[$key]);
        }

        // remove prefix from field's name
        $keys = array_map(function ($key) use ($prefix) {
            return substr($key, strlen($prefix.'_'));
        }, array_keys($prefixed));

        // add new field to the result
        if (empty(array_filter($prefixed))) {
            $result[$prefix] = null;
        } else {
            $result[$prefix] = array_combine($keys, $prefixed);
        }

        return $result;
    }
}
