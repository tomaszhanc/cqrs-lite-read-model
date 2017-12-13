<?php
declare(strict_types=1);

namespace ReadModel\Bridge\Doctrine;

use Doctrine\DBAL\Query\QueryBuilder;
use ReadModel\WalkablePaginator;
use ReadModel\Walker\ResultWalker;

class DbalWalkablePaginator extends WalkablePaginator
{
    use DbalPaginatorCapabilities;

    /** @var QueryBuilder */
    private $qb;

    public function __construct(QueryBuilder $qb, ?int $limit, int $offset, ResultWalker $resultWalker = null)
    {
        parent::__construct($limit, $offset, $resultWalker);
        $this->qb = $qb;
    }
}
