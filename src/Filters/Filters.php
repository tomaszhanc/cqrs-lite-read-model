<?php
declare(strict_types=1);

namespace ReadModel\Filters;

use Assert\Assertion;
use ReadModel\InvalidArgumentException;
use ReadModel\Paginator;

final class Filters
{
    /** @var Filter[] */
    private $filters;

    /** @var string[] */
    private $unusedFilters;

    /** @var OrderBy[] */
    private $ordersBy;

    /** @var string[] */
    private $unusedOrdersBy;

    /** @var int */
    private $limit;

    /** @var int */
    private $offset;

    public function __construct(array $filters, array $ordersBy, int $limit = null, int $offset = 0)
    {
        Assertion::allIsInstanceOf($filters, Filter::class);
        Assertion::allIsInstanceOf($ordersBy, OrderBy::class);

        $this->limit = $limit;
        $this->offset = $offset;
        $this->setFilters($filters);
        $this->setOrdersBy($ordersBy);
    }

    public function limit(): ?int
    {
        return $this->limit;
    }

    public function offset(): int
    {
        return $this->offset;
    }

    public function useFilter(string $name)
    {
        $this->guardAgainstInvalidFilter($name);
        unset($this->unusedFilters[$name]);
        return $this->filters[$name]->transformedValue();
    }

    public function useOrderBy(string $name): ?OrderBy
    {
        unset($this->unusedOrdersBy[$name]);
        return $this->ordersBy[$name] ?? null;
    }

    /** @return Filter[] */
    public function unusedFilters(): \Generator
    {
        foreach ($this->unusedFilters as $filter) {
            $this->useFilter($filter);
            yield $this->filters[$filter];
        }
    }

    /** @return OrderBy[] */
    public function unusedOrdersBy(): \Generator
    {
        foreach ($this->unusedOrdersBy as $orderBy) {
            yield $this->useOrderBy($orderBy);
        }
    }

    public function addMetaToPaginator(Paginator $paginator): void
    {
        // callables are used, because filters and orders by might be used after calling that method

        $paginator->addMeta('query', function () {
            foreach ($this->filters as $filter) {
                if (!isset($this->unusedFilters[$filter->name()])) {
                    // only for used filters
                    $queries[$filter->name()] = $filter->simplifiedValue();
                }
            }

            return $queries ?? [];
        });

        $paginator->addMeta('order', function () {
            foreach ($this->ordersBy as $orderBy) {
                if (!isset($this->unusedOrdersBy[$orderBy->field()])) {
                    // only for used orders by
                    $orders[] = (string) $orderBy;
                }
            }

            return isset($orders) ? implode(',', $orders) : null;
        });
    }

    private function guardAgainstInvalidFilter(string $name): void
    {
        if (!isset($this->filters[$name])) {
            throw new InvalidArgumentException("Filter '$name' doesn't exist");
        }
    }

    private function setFilters(array $filters): void
    {
        $keys = array_map(function (Filter $filter) {
            return $filter->name();
        }, $filters);

        $this->filters = array_combine($keys, $filters);
        $this->unusedFilters = array_combine($keys, $keys);
    }

    private function setOrdersBy(array $ordersBy): void
    {
        $keys = array_map(function (OrderBy $orderBy) {
            return $orderBy->field();
        }, $ordersBy);

        $this->ordersBy = array_combine($keys, $ordersBy);
        $this->unusedOrdersBy = array_combine($keys, $keys);
    }
}
