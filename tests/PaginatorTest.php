<?php
declare(strict_types=1);

namespace ReadModel\Tests;

use ReadModel\Paginator;
use PHPUnit\Framework\TestCase;
use ReadModel\Tests\Fixtures\TestPaginator;

class PaginatorTest extends TestCase
{
    /** @var Paginator */
    private $paginator;

    protected function setUp()
    {
        $this->paginator = new TestPaginator(100, 10,
            ['one', 'two']
        );
    }

    /**
     * @test
     */
    public function should_returns_meta()
    {
        $meta = $this->paginator->getMeta();

        $this->assertEquals([
            'limit' => 100,
            'offset' => 10,
            'total' => 2
        ], $meta);
    }

    /**
     * @test
     */
    public function should_add_static_meta_data()
    {
        $this->paginator->addMeta('query', ['name' => 'test']);
        $meta = $this->paginator->getMeta();

        $this->assertSame([
            'limit' => 100,
            'offset' => 10,
            'total' => 2,
            'query' => ['name' => 'test']
        ], $meta);
    }

    /**
     * @test
     */
    public function should_add_dynamic_meta_data()
    {
        $this->paginator->addMeta('order', function () {
            return '-name,dateOfBirth';
        });
        $meta = $this->paginator->getMeta();

        $this->assertSame([
            'limit' => 100,
            'offset' => 10,
            'total' => 2,
            'order' => '-name,dateOfBirth'
        ], $meta);
    }
}
