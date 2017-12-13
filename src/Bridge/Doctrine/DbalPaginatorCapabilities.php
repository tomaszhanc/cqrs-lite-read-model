<?php
declare(strict_types=1);

namespace ReadModel\Bridge\Doctrine;

trait DbalPaginatorCapabilities
{
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
        $countFrom = $this->getCountFrom();

        $qb->select("COUNT($countFrom)")
           ->setMaxResults(null)
           ->setFirstResult(null)
           ->resetQueryPart('orderBy');

        return (int) $qb->execute()->fetchColumn();
    }

    protected function getCountFrom(): string
    {
        $select = $this->qb->getQueryPart('select');

        if (!is_array($select)) {
            return '*';
        }

        $select = explode(',', array_shift($select));

        return array_shift($select);
    }
}
