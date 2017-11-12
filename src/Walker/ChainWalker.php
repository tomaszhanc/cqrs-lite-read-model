<?php
declare(strict_types=1);

namespace ReadModel\Walker;

class ChainWalker implements ResultWalker
{
    /** @var ResultWalker[] */
    private $walkers;

    public function __construct(ResultWalker ...$walkers)
    {
        $this->walkers = $walkers;
    }

    public function walk(array $result): array
    {
        foreach ($this->walkers as $walker) {
            $result = $walker->walk($result);
        }

        return $result;
    }
}
