<?php
declare(strict_types=1);

namespace ReadModel\Bridge\Doctrine\Query;

use Doctrine\DBAL\Query\QueryBuilder;
use Ramsey\Uuid\Exception\InvalidUuidStringException;
use Ramsey\Uuid\Uuid;
use ReadModel\Walker\WalkerBuilder;

trait BinaryUuidSupport
{
    protected function transformParameters(QueryBuilder $qb, string ...$parameters): void
    {
        $this->transformUuidsToBytes($qb, $parameters);
    }

    protected function createWalkerBuilder(array $uuids): WalkerBuilder
    {
        $uuids = array_merge(['id'], $uuids);

        return parent::createWalkerBuilder($uuids)->withBinaryUuidCasting(...$uuids);
    }

    private function transformUuidsToBytes(QueryBuilder $qb, array $parameters): void
    {
        $parameters = array_merge(['id'], $parameters);

        foreach ($qb->getParameters() as $key => $value) {
            if (empty($value) || !in_array($key, $parameters)) {
                continue;
            }

            try {
                $value = Uuid::fromString($value)->getBytes();
                $qb->setParameter($key, $value);
            } catch (InvalidUuidStringException $e) {
                // just skip it
            }
        }
    }
}
