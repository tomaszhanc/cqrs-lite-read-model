<?php
declare(strict_types=1);

namespace ReadModel\Filters;

use ReadModel\Filters\DataTransformer as T;
use ReadModel\InvalidArgumentException;

abstract class FiltersBuilder
{
    /** @var OrderBy */
    protected $defaultOrder;

    /** @var ?int */
    protected $defaultLimit;

    /** @var int */
    protected $defaultOffset;

    /** @var array */
    private $filters = [];

    /** @var array */
    private $ordersBy = [];

    public function __construct(string $defaultOrder, int $defaultLimit = null, int $defaultOffset = 0)
    {
        $this->defaultOrder = new OrderBy($defaultOrder);
        $this->defaultLimit = $defaultLimit;
        $this->defaultOffset = $defaultOffset;
        $this->addOrderBy($this->defaultOrder->field());
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
            return [$this->defaultOrder];
        }

        $orders = array_map(function (string $order) {
            return new OrderBy(trim($order));
        }, explode(',', $order));

        $this->guardAgainstInvalidOrder($orders);

        return $orders;
    }

    private function guardAgainstInvalidOrder(array $orders)
    {
        sort($this->ordersBy);

        foreach ($orders as $order) {
            if (!in_array($order->field(), $this->ordersBy)) {
                throw new InvalidArgumentException(sprintf(
                    'Invalid order field [%s]. You can order by: %s',
                    $order->field(),
                    implode(', ', $this->ordersBy)
                ));
            }
        }
    }

    abstract protected function getValue(string $name, $defaultValue);

    abstract protected function getLimit(): ?int;

    abstract protected function getOffset(): int;

    abstract protected function getOrderBy(): ?string;
}
