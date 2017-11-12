<?php
declare(strict_types=1);

namespace ReadModel\Filters\DataTransformer;

class DateTimeTransformer implements DataTransformer
{
    private $simplifyFormat;

    public function __construct($simplifyFormat = 'Y-m-d H:i')
    {
        $this->simplifyFormat = $simplifyFormat;
    }

    public function transform($value)
    {
        $this->validate($value);

        if ($value) {
            return new \DateTimeImmutable($value);
        }

        return $value;
    }

    public function simplify($value)
    {
        $date = $this->transform($value);

        if ($date instanceof \DateTimeInterface) {
            return $date->format($this->simplifyFormat);
        }

        return $date;
    }

    private function validate($value)
    {
        try {
            new \DateTimeImmutable($value);
        } catch (\Exception $e) {
            throw new InvalidValueException('datetime', $value);
        }
    }
}
