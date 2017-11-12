<?php
declare(strict_types=1);

namespace ReadModel\Tests\Walker;

use ReadModel\Walker\KeysToCamelCaseWalker;
use PHPUnit\Framework\TestCase;

class KeysToCamelCaseWalkerTest extends TestCase
{
    /**
     * @test
     */
    public function should_transform_all_keys_to_camel_case()
    {
        $walker = new KeysToCamelCaseWalker();

        $this->assertSame([
            'firstName' => 'John',
            'lastName' => 'Snow',
            'someLongNameForTestingPurposes' => [
                'andAnotherOne' => 'unknown'
            ],
        ], $walker->walk([
            'first_name' => 'John',
            'LastName' => 'Snow',
            'some_long_name_for_testing_purposes' => [
                'and_another_one' => 'unknown'
            ]
        ]));
    }
}
