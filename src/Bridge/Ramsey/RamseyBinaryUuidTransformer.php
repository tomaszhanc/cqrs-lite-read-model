<?php
declare(strict_types=1);

namespace ReadModel\Bridge\Ramsey;

use Ramsey\Uuid\Uuid;
use ReadModel\Walker\BinaryUuidTransformer;

class RamseyBinaryUuidTransformer implements BinaryUuidTransformer
{
    public function transformToString($value): ?string
    {
        if (empty($value)) {
            return null;
        }

        return Uuid::fromBytes($value)->toString();
    }
}
