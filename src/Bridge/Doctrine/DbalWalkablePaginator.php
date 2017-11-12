<?php
declare(strict_types=1);

namespace ReadModel\Bridge\Doctrine;

use Doctrine\DBAL\Query\QueryBuilder;
use ReadModel\WalkablePaginator;
use ReadModel\Walker\ResultWalker;

class DbalWalkablePaginator extends WalkablePaginator
{
    /** @var QueryBuilder */
    private $qb;

    public function __construct(QueryBuilder $qb, ?int $limit, int $offset, ResultWalker $resultWalker = null)
    {
        parent::__construct($limit, $offset, $resultWalker);
        $this->qb = $qb;
    }

    protected function findAll(): array
    {
        $this->qb
            ->setMaxResults($this->limit)
            ->setFirstResult($this->offset);

        return $this->qb->execute()->fetchAll();
    }

    protected function getTotal(): int
    {
        $qb = clone $this->qb;

        $qb->select('COUNT(*)')
            ->setMaxResults(null)
            ->setFirstResult(null);
        $qb->resetQueryPart('orderBy');

        return (int) $qb->execute()->fetchColumn();
    }
}
