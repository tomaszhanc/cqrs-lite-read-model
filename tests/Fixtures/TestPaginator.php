<?php
declare(strict_types=1);

namespace ReadModel\Tests\Fixtures;

use ReadModel\Paginator;

class TestPaginator extends Paginator
{
    /** @var array */
    private $data;

    public function __construct(?int $limit, int $offset, array $data)
    {
        parent::__construct($limit, $offset);
        $this->data = $data;
    }

    protected function findAll(): array
    {
        return $this->data;
    }

    protected function getTotal(): int
    {
        return count($this->data);
    }
}
