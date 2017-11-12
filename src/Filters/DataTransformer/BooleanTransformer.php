<?php
declare(strict_types=1);

namespace ReadModel\Filters\DataTransformer;

class BooleanTransformer implements DataTransformer
{
    public function transform($value)
    {
        if (in_array($value, [1, true, 'true', '1'], true)) {
            return true;
        }

        if (in_array($value, [0, false, 'false', '0', null], true)) {
            return false;
        }

        throw new InvalidValueException('boolean', $value);
    }

    public function simplify($value)
    {
        return $this->transform($value);
    }
}
