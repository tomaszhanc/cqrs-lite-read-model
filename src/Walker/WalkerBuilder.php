<?php
declare(strict_types=1);

namespace ReadModel\Walker;

final class WalkerBuilder
{
    /** @var BinaryTransformer */
    private $transformer;

    /** @var ResultWalker[] */
    private $walkers = [];

    /** @var callable[] */
    private $callables = [];

    /** @var array */
    private $prefixes = [];

    /** @var bool */
    private $camelCase = false;

    public function __construct(BinaryTransformer $transformer = null)
    {
        $this->transformer = $transformer;
    }

    public function addWalker(ResultWalker $walker): self
    {
        $this->walkers[] = $walker;
        return $this;
    }

    /**
     * Callable walkers are added as second walker from the end
     */
    public function with(callable $callable): self
    {
        $this->callables[] = $callable;
        return $this;
    }

    public function withBinaryCasting(...$keys): self
    {
        if ($this->transformer === null) {
            throw new \InvalidArgumentException('You have to provide BinaryTransformer to use BinaryTransformerWalker');
        }

        return $this->addWalker(new BinaryTransformerWalker($this->transformer, ...$keys));
    }

    public function withScalarCasting(array $mapping): self
    {
        return $this->addWalker(new ScalarTransformerWalker($mapping));
    }

    public function withEmbedded(...$prefixes): self
    {
        return $this->prefixes = array_merge($this->prefixes, $prefixes);
    }

    /**
     * It would be the latest walker which would walk through results.
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

        if ($this->camelCase) {
            // it always should be the latest walker
            $this->addWalker(new KeysToCamelCaseWalker());
        }

        $walker = new ChainWalker(...$this->walkers);
        $this->clearBuilder();

        return $walker;
    }

    private function clearBuilder()
    {
        $this->walkers = [];
        $this->prefixes = [];
        $this->callables = [];
        $this->camelCase = false;
    }
}
