<?php
declare(strict_types=1);

namespace ReadModel\Bridge\Symfony;

use ReadModel\Filters\FiltersBuilder;
use Symfony\Component\HttpFoundation\Request;

trait RequestFiltersBuilderFactory
{
    protected function createFiltersBuilder(
        Request $request,
        string $defaultOrder = null,
        int $defaultLimit = null,
        int $defaultOffset = 0
    ): FiltersBuilder {
        return new RequestFiltersBuilder($request, $defaultOrder, $defaultLimit, $defaultOffset);
    }
}
