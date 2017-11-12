<?php
declare(strict_types=1);

namespace ReadModel\Bridge\Doctrine\Query;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use ReadModel\Bridge\Doctrine\ApplyFilters;
use ReadModel\Bridge\Doctrine\DbalPaginator;
use ReadModel\Filters\Filters;
use ReadModel\Paginator;

abstract class DbalQuery
{
    /** @var Connection */
    protected $connection;

    /** @var int */
    protected $defaultLimit = 100;

    /** @var array */
    protected $columnMapping = [];

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    protected function createQueryBuilder(): QueryBuilder
    {
        return $this->connection->createQueryBuilder();
    }

    protected function createPaginator(QueryBuilder $qb, int $limit = null, $offset = 0, string ...$parameters): Paginator
    {
        $limit = $limit ?? $this->defaultLimit;
        $this->transformParameters($qb, ...$parameters);

        return new DbalPaginator($qb, $limit, $offset);
    }

    protected function createPaginatorForFilters(QueryBuilder $qb, Filters $filters, string ...$parameters): Paginator
    {
        $paginator = $this->createPaginator($qb, $filters->limit(), $filters->offset(), ...$parameters);
        $filters->addMetaToPaginator($paginator);

        return $paginator;
    }

    protected function applyFilters(QueryBuilder $qb, Filters $filters): void
    {
        (new ApplyFilters($this->columnMapping))($qb, $filters);
    }

    protected function getResult(QueryBuilder $qb, string ...$parameters): array
    {
        $this->transformParameters($qb, ...$parameters);

        return $qb->execute()->fetchAll();
    }

    protected function getScalarResult(QueryBuilder $qb, string ...$parameters): array
    {
        return array_map(function (array $row) {
            return $this->getColumn($row);
        }, $this->getResult($qb, ...$parameters));
    }

    protected function getSingleResult(QueryBuilder $qb, string ...$parameters): array
    {
        $this->transformParameters($qb, ...$parameters);
        $qb->setMaxResults(1);
        $result = $qb->execute()->fetch();

        if ($result === false) {
            throw new NotFoundException();
        }

        return $result;
    }

    protected function getSingleScalarResult(QueryBuilder $qb, ...$parameters)
    {
        return $this->getColumn(
            $this->getSingleResult($qb, ...$parameters)
        );
    }

    protected function transformParameters(QueryBuilder $qb, string ...$parameters): void
    {
        // do nothing, you can override this method or use trait TransformUuidToBytes
    }

    private function getColumn(array $result)
    {
        if (count($result) > 1) {
            throw new TooManyColumnsException(array_keys($result));
        }

        return array_shift($result);
    }
}
