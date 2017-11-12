<?php
declare(strict_types=1);

namespace ReadModel\Tests\Fixtures;

use ReadModel\WalkablePaginator;
use ReadModel\Walker\ResultWalker;

class TestWalkablePaginator extends WalkablePaginator
{
    /** @var array */
    private $data;

    public function __construct(?int $limit, int $offset, array $data, ResultWalker $walker = null)
    {
        parent::__construct($limit, $offset, $walker);
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
