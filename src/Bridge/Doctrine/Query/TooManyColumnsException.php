<?php
declare(strict_types=1);

namespace ReadModel\Bridge\Doctrine\Query;

class TooManyColumnsException extends \Exception
{
    public function __construct(array $fields)
    {
        parent::__construct(sprintf(
            "For a scalar query you should select only one column, but %d were selected: [%s]",
            count($fields),
            implode(', ', $fields)
        ));
    }
}
