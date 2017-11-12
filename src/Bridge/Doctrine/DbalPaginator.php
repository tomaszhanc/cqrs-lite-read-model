<?php
declare(strict_types=1);

namespace ReadModel\Bridge\Doctrine;

use Doctrine\DBAL\Query\QueryBuilder;
use ReadModel\Paginator;

class DbalPaginator extends Paginator
{
    /** @var QueryBuilder */
    private $qb;

    public function __construct(QueryBuilder $qb, ?int $limit, int $offset)
    {
        parent::__construct($limit, $offset);
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
