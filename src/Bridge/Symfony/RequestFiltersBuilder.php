<?php
declare(strict_types=1);

namespace ReadModel\Bridge\Symfony;

use ReadModel\Filters\FiltersBuilder;
use Symfony\Component\HttpFoundation\Request;

class RequestFiltersBuilder extends FiltersBuilder
{
    /** @var Request */
    private $request;

    public function __construct(Request $request, string $defaultOrder, int $defaultLimit = null, int $defaultOffset = 0)
    {
        parent::__construct($defaultOrder, $defaultLimit, $defaultOffset);
        $this->request = $request;
    }

    protected function getValue(string $name, $defaultValue)
    {
        return $this->request->query->get($name, $defaultValue);
    }

    protected function getLimit(): ?int
    {
        return $this->request->query->getInt('limit', $this->defaultLimit);
    }

    protected function getOffset(): int
    {
        return $this->request->query->getInt('offset', $this->defaultOffset);
    }

    protected function getOrderBy(): ?string
    {
        return $this->request->query->get('order');
    }
}
