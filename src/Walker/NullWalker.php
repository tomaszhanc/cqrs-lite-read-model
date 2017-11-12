<?php
declare(strict_types=1);

namespace ReadModel\Walker;

class NullWalker implements ResultWalker
{
    public function walk(array $result): array
    {
        return $result;
    }
}
