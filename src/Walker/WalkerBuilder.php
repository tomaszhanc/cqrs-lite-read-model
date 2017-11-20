<?php
declare(strict_types=1);

namespace ReadModel\Walker;

use ReadModel\InvalidArgumentException;

final class WalkerBuilder
{
    /** @var BinaryUuidTransformer */
    private $transformer;

    /** @var ResultWalker[] */
    private $walkers = [];

    /** @var callable[] */
    private $callables = [];

    /** @var array */
    private $prefixes = [];

    /** @var array */
    private $binaryKeys = [];

    /** @var array */
    private $scalarMapping = [];

    /** @var bool */
    private $camelCase = false;

    public function __construct(BinaryUuidTransformer $transformer = null)
    {
        $this->transformer = $transformer;
    }

    public function addWalker(ResultWalker $walker): self
    {
        $this->walkers[] = $walker;
        return $this;
    }

    /**
     * Embed walker is added as the first walker.
     */
    public function withEmbedded(...$prefixes): self
    {
        $this->prefixes = array_merge($this->prefixes, $prefixes);
        return $this;
    }


    /**
     * Callable walkers are added after embed walker.
     */
    public function with(callable $callable): self
    {
        $this->callables[] = $callable;
        return $this;
    }

    /**
     * Binary casting is added after callable walkers.
     */
    public function withBinaryUuidCasting(...$keys): self
    {
        if ($this->transformer === null) {
            throw InvalidArgumentException::binaryUuidTransformerMustBeProvided();
        }

        $this->binaryKeys = array_merge($this->binaryKeys, $keys);
        return $this;
    }

    /**
     * Scalar casting is added after binary casting walker.
     */
    public function withScalarCasting(array $mapping): self
    {
        $this->scalarMapping = array_merge($this->scalarMapping, $mapping);
        return $this;
    }

    /**
     * Camel cased walker is added as the last walker.
     */
    public function withCamelCasedFieldNames(): self
    {
        $this->camelCase = true;
        return $this;
    }

    public function build(): ResultWalker
    {
        if (!empty($this->prefixes)) {
            $this->addWalker(new EmbedWalker(...$this->prefixes));
        }

        foreach ($this->callables as $callable) {
            $this->addWalker(new CallableWalker($callable));
        }

        if (!empty($this->binaryKeys)) {
            $this->addWalker(new BinaryUuidTransformerWalker($this->transformer, ...$this->binaryKeys));
        }

        if (!empty($this->scalarMapping)) {
            $this->addWalker(new ScalarTransformerWalker($this->scalarMapping));
        }

        if ($this->camelCase) {
            $this->addWalker(new KeysToCamelCaseWalker());
        }

        $walker = new ChainWalker(...$this->walkers);
        $this->clearBuilder();

        return $walker;
    }

    private function clearBuilder(): void
    {
        $this->walkers = [];
        $this->callables = [];
        $this->prefixes = [];
        $this->binaryKeys = [];
        $this->scalarMapping = [];
        $this->camelCase = false;
    }
}
