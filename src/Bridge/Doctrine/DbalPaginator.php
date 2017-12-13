<?php
declare(strict_types=1);

namespace ReadModel\Bridge\Doctrine;

use Doctrine\DBAL\Query\QueryBuilder;
use ReadModel\Paginator;

class DbalPaginator extends Paginator
{
    use DbalPaginatorCapabilities;

    /** @var QueryBuilder */
    private $qb;

    public function __construct(QueryBuilder $qb, ?int $limit, int $offset)
    {
        parent::__construct($limit, $offset);
        $this->qb = $qb;
    }
}
