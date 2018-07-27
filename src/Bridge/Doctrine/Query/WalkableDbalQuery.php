<?php
declare(strict_types=1);

namespace ReadModel\Bridge\Doctrine\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use ReadModel\Bridge\Doctrine\DbalWalkablePaginator;
use ReadModel\Paginator;
use ReadModel\Walker\ResultWalker;
use ReadModel\Walker\WalkerBuilder;

abstract class WalkableDbalQuery extends DbalQuery
{
    /** @var WalkerBuilder */
    private $walkerBuilder;

    /** @var array */
    protected $typeMapping = [];

    public function __construct(Connection $connection, WalkerBuilder $walkerBuilder)
    {
        parent::__construct($connection);
        $this->walkerBuilder = $walkerBuilder;
    }

    protected function createPaginator(QueryBuilder $qb, int $limit = null, $offset = 0, string ...$parameters): Paginator
    {
        $limit = $limit;
        $this->transformParameters($qb, ...$parameters);

        return new DbalWalkablePaginator($qb, $limit, $offset, $this->buildWalker($parameters));
    }

    protected function getResult(QueryBuilder $qb, string ...$parameters): array
    {
        $walker = $this->buildWalker($parameters);

        return array_map(function ($result) use ($walker) {
            return $walker->walk($result);
        }, parent::getResult($qb, ...$parameters));
    }

    protected function getSingleResult(QueryBuilder $qb, string ...$parameters): array
    {
        return $this->buildWalker($parameters)->walk(
            parent::getSingleResult($qb, ...$parameters)
        );
    }

    protected function embed(...$prefixes): void
    {
        $this->walkerBuilder->withEmbedded(...$prefixes);
    }

    protected function add(callable $callable): void
    {
        $this->walkerBuilder->with($callable);
    }

    protected function createWalkerBuilder(array $uuids): WalkerBuilder
    {
        return $this->walkerBuilder
            ->withCamelCasedFieldNames()
            ->withScalarCasting($this->typeMapping);
    }

    protected function buildWalker(array $uuids): ResultWalker
    {
        return $this->createWalkerBuilder($uuids)->build();
    }
}
