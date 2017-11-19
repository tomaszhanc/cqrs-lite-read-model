<?php
declare(strict_types=1);

namespace ReadModel\Tests\Walker;

use ReadModel\Walker\BinaryUuidTransformer;
use ReadModel\Walker\BinaryUuidTransformerWalker;
use ReadModel\Walker\CallableWalker;
use ReadModel\Walker\ChainWalker;
use ReadModel\Walker\EmbedWalker;
use ReadModel\Walker\KeysToCamelCaseWalker;
use ReadModel\Walker\ScalarTransformerWalker;
use ReadModel\Walker\WalkerBuilder;
use PHPUnit\Framework\TestCase;

class WalkerBuilderTest extends TestCase
{
    /** @var WalkerBuilder */
    private $builder;

    protected function setUp()
    {
        $this->builder = new WalkerBuilder($this->prophesize(BinaryUuidTransformer::class)->reveal());
        $this->builder->withCamelCasedFieldNames();
        $this->builder->withScalarCasting(['active' => 'bool']);
        $this->builder->withBinaryUuidCasting('id');
        $this->builder->with(function () {});
        $this->builder->with(function () {});
        $this->builder->withEmbedded('prefix');
    }

    /**
     * @test
     */
    public function should_build_chain_walker_in_a_proper_order()
    {
        $chainWalker = $this->builder->build();
        $walkers = $this->getWalkersArray($chainWalker);

        $this->assertCount(6, $walkers);
        $this->assertInstanceOf(EmbedWalker::class, $walkers[0]);
        $this->assertInstanceOf(CallableWalker::class, $walkers[1]);
        $this->assertInstanceOf(CallableWalker::class, $walkers[2]);
        $this->assertInstanceOf(BinaryUuidTransformerWalker::class, $walkers[3]);
        $this->assertInstanceOf(ScalarTransformerWalker::class, $walkers[4]);
        $this->assertInstanceOf(KeysToCamelCaseWalker::class, $walkers[5]);
    }

    /**
     * @test
     */
    public function should_clear_builder_after_build()
    {
        $this->builder->build();

        $this->assertAttributeEmpty('walkers', $this->builder);
        $this->assertAttributeEmpty('prefixes', $this->builder);
        $this->assertAttributeEmpty('callables', $this->builder);
        $this->assertAttributeEmpty('binaryKeys', $this->builder);
        $this->assertAttributeEmpty('scalarMapping', $this->builder);
        $this->assertAttributeEquals(false, 'camelCase', $this->builder);
    }

    protected function getWalkersArray(ChainWalker $chainWalker): array
    {
        $ref = new \ReflectionObject($chainWalker);
        $refProperty = $ref->getProperty('walkers');
        $refProperty->setAccessible(true);

        return $refProperty->getValue($chainWalker);
    }
}
