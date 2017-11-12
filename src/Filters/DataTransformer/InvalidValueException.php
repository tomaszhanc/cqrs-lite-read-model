<?php
declare(strict_types=1);

namespace ReadModel\Filters\DataTransformer;

use ReadModel\InvalidArgumentException;

class InvalidValueException extends InvalidArgumentException
{
    public function __construct(string $filterName, $value)
    {
        $message = sprintf("Invalid value '%s' for %s filter", $value, $filterName);
        parent::__construct($message);
    }
}
