<?php
declare(strict_types=1);

namespace ReadModel\Filters;

final class OrderBy
{
    /** @var string */
    private $field;

    /** @var string */
    private $order;

    /**
     * @param string $orderBy, eg: name (for ascending order), -name (for descending order)
     */
    public function __construct(string $orderBy)
    {
        if ($orderBy[0] === '-') {
            $this->field = substr($orderBy, 1);
            $this->order = 'desc';
        } else {
            $this->field = $orderBy;
            $this->order = 'asc';
        }
    }

    public function field(): string
    {
        return $this->field;
    }

    public function order(): string
    {
        return $this->order;
    }

    public function __toString(): string
    {
        $order = $this->order === 'desc' ? '-' : '';
        return $order.$this->field;
    }
}
