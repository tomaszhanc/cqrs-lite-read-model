<?php
declare(strict_types=1);

namespace ReadModel\Walker;

interface BinaryTransformer
{
    public function transformToString($value): string;
}
