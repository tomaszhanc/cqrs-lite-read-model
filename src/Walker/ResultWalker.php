<?php
declare(strict_types=1);

namespace ReadModel\Walker;

interface ResultWalker
{
    public function walk(array $result): array;
}
