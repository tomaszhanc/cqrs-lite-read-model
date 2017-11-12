<?php
declare(strict_types=1);

namespace ReadModel\Walker;

use ReadModel\InvalidArgumentException;

class ScalarTransformerWalker implements ResultWalker
{
    /** @var array */
    private $typeMapping;

    /**
     * @param array $typeMapping e.g: ['id' => 'int', 'active' => 'bool']
     */
    public function __construct(array $typeMapping)
    {
        $this->typeMapping = $typeMapping;
    }

    public function walk(array $result): array
    {
        array_walk($result, function (&$value, $key) {
            if ($value === null || !isset($this->typeMapping[$key])) {
                return;
            }

            $value = $this->transform($value, $this->typeMapping[$key]);
        });

        return $result;
    }

    private function transform($value, $type)
    {
        $type = strtolower($type);
        $type = 'boolean' == $type ? 'bool' : $type;
        $function = $type.'val';

        if (function_exists($function)) {
            return call_user_func($function, $value);
        }

        throw new InvalidArgumentException(sprintf(
            'Type "%s" is invalid. There is no "%s" function. Fix your type or add that function to global namespace',
            $type,
            $function
        ));
    }
}
