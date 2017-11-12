<?php
declare(strict_types=1);

namespace ReadModel;

abstract class Paginator
{
    /** @var int */
    protected $limit;

    /** @var int */
    protected $offset;

    /** @var array */
    protected $meta = [];

    public function __construct(?int $limit, int $offset)
    {
        $this->limit = $limit;
        $this->offset = $offset;
    }

    /**
     * @param string $name
     * @param mixed  $value also a callable which will be resolved when meta data would be needed; see resolveMeta()
     */
    public function addMeta(string $name, $value): void
    {
        $this->meta[$name] = $value;
    }

    public function getResults(): array
    {
        return $this->findAll();
    }

    public function getMeta(): array
    {
        return array_merge([
            'limit' => $this->limit,
            'offset' => $this->offset,
            'total' => $this->getTotal()
        ], $this->resolveMeta());
    }

    private function resolveMeta(): array
    {
        return array_map(function ($value) {
            // execute callables
            if (is_callable($value)) {
                $value = call_user_func($value);
            }

            return $value;
        }, $this->meta);
    }

    abstract protected function findAll(): array;

    abstract protected function getTotal(): int;
}
