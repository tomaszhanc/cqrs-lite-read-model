<?php
declare(strict_types=1);

namespace ReadModel\Filters;

use ReadModel\Filters\DataTransformer as T;

abstract class FiltersBuilder
{
    /** @var OrderBy|null */
    protected $defaultOrder;

    /** @var int|null */
    protected $defaultLimit;

    /** @var int */
    protected $defaultOffset;

    /** @var array */
    private $filters = [];

    /** @var array */
    private $ordersBy = [];

    public function __construct(string $defaultOrder = null, int $defaultLimit = null, int $defaultOffset = 0)
    {
        $this->defaultLimit = $defaultLimit;
        $this->defaultOffset = $defaultOffset;

        if ($defaultOrder !== null) {
            $this->defaultOrder = new OrderBy($defaultOrder);
            $this->addOrderBy($this->defaultOrder->field());
        }
    }

    public function addFilter(string $name, $defaultValue = null, T\DataTransformer $transformer = null): self
    {
        $value = $this->getValue($name, $defaultValue);
        $this->filters[$name] = new Filter($name, $value, $transformer);
        return $this;
    }

    public function addIntegerFilter(string $name, $defaultValue = null): self
    {
        return $this->addFilter($name, $defaultValue, new T\IntegerTransformer());
    }

    public function addFloatFilter(string $name, $defaultValue = null): self
    {
        return $this->addFilter($name, $defaultValue, new T\FloatTransformer());
    }

    public function addBooleanFilter(string $name, $defaultValue = null): self
    {
        return $this->addFilter($name, $defaultValue, new T\BooleanTransformer());
    }

    public function addDateTimeFilter(string $name, $defaultValue = null, $format = 'Y-m-d H:i'): self
    {
        return $this->addFilter($name, $defaultValue, new T\DateTimeTransformer($format));
    }

    public function addDateFilter(string $name, $defaultValue = null): self
    {
        return $this->addFilter($name, $defaultValue, new T\DateTimeTransformer('Y-m-d'));
    }

    /**
     * Supports also comma separated values, eg: 'tag1,tag2,tag3'
     */
    public function addArrayFilter(string $name, $defaultValues = null): self
    {
        $value = $this->getValue($name, $defaultValues);

        if (is_string($value)) {
            $value = explode(',', $value);
        }

        $this->filters[$name] = new Filter($name, $value, new T\ArrayTransformer());
        return $this;
    }

    public function addOrderBy(string ...$fields): self
    {
        foreach ($fields as $field) {
            if (in_array($field, $this->ordersBy)) {
                continue;
            }

            $this->ordersBy[] = $field;
        }

        return $this;
    }

    public function buildFilters(): Filters
    {
        return new Filters($this->filters, $this->resolveOrderBy(), $this->getLimit(), $this->getOffset());
    }

    private function resolveOrderBy(): array
    {
        $order = $this->getOrderBy();

        if ($order === null) {
            return array_filter([$this->defaultOrder]);
        }

        $orders = array_map(function (string $order) {
            return new OrderBy(trim($order));
        }, explode(',', $order));

        return array_filter($orders, function (OrderBy $orderBy) {
            return in_array($orderBy->field(), $this->ordersBy);
        });
    }

    abstract protected function getValue(string $name, $defaultValue);

    abstract protected function getLimit(): ?int;

    abstract protected function getOffset(): int;

    abstract protected function getOrderBy(): ?string;
}
