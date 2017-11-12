<?php
declare(strict_types=1);

namespace ReadModel\Walker;

interface BinaryUuidTransformer
{
    public function transformToString($value): ?string;
}
