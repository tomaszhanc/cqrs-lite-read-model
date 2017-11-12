<?php
declare(strict_types=1);

namespace ReadModel\Bridge\Doctrine;

use Doctrine\DBAL\Query\QueryBuilder;
use ReadModel\Filters\Filters;

final class ApplyFilters
{
    /** @var array */
    private $columnMapping;

    /**
     * @param array $columnMapping to provide columnMapping from filters to db, eg: city => address_city
     */
    public function __construct(array $columnMapping = [])
    {
        $this->columnMapping = $columnMapping;
    }

    /**
     * Applies unused filters and unused orders by to the query.
     */
    public function __invoke(QueryBuilder $qb, Filters $filters)
    {
        // add filters to a query
        foreach ($filters->unusedFilters() as $filter) {
            $value = $filter->simplifiedValue();

            if ($value === null) {
                continue;
            }

            $columnName = $this->getColumnName($filter->name());
            $qb->andWhere(sprintf('%s = :%s', $columnName, $filter->name()))
               ->setParameter($filter->name(), $value);
        }

        // add orders by to a query
        foreach ($filters->unusedOrdersBy() as $orderBy) {
            $columnName = $this->getColumnName($orderBy->field());
            $qb->addOrderBy($columnName, $orderBy->order());
        }
    }

    private function getColumnName(string $name): string
    {
        $name = $this->columnMapping[$name] ?? $name;
        return $this->toUnderscore($name);
    }

    private function toUnderscore(string $value): string
    {
        return strtolower(preg_replace('/[A-Z]/', '_\\0', lcfirst($value)));
    }
}
