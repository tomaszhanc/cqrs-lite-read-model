<?php
declare(strict_types=1);

namespace ReadModel\Walker;

class CallableWalker implements ResultWalker
{
    /** @var callable */
    private $callable;

    public function __construct(callable $callable)
    {
        $this->callable = $callable;
    }

    public function walk(array $result): array
    {
        return call_user_func($this->callable, $result);
    }
}
