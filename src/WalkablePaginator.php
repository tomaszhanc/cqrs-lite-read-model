<?php
declare(strict_types=1);

namespace ReadModel;

use Assert\Assertion;
use ReadModel\Walker\NullWalker;
use ReadModel\Walker\ResultWalker;

abstract class WalkablePaginator extends Paginator
{
    /** @var ResultWalker */
    protected $resultWalker;

    public function __construct(?int $limit, int $offset, ResultWalker $resultWalker = null)
    {
        parent::__construct($limit, $offset);
        $this->resultWalker = $resultWalker ?? new NullWalker();
    }

    /**
     * @return array<array>
     */
    public function getResults(): array
    {
        $results = parent::getResults();
        Assertion::allIsArray($results, 'For WalkablePaginator getResults() method must return array of arrays');

        return array_map(function (array $row) {
            return $this->resultWalker->walk($row);
        }, $results);
    }
}
