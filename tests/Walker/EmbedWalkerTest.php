<?php
declare(strict_types=1);

namespace ReadModel\Tests\Walker;

use ReadModel\Walker\EmbedWalker;
use PHPUnit\Framework\TestCase;

class EmbedWalkerTest extends TestCase
{
    /**
     * @test
     */
    public function should_embed_result()
    {
        $input = [
            'id' => 5,
            'name' => 'Test',
            'address_street' => 'Sesame',
            'address_house_number' => 21,
            'address_flat_number' => 5,
            'book_isbn' => 'isbn',
            'book_title' => 'title',
            'car_make' => null,
            'car_model' => null
        ];

        $output = [
            'id' => 5,
            'name' => 'Test',
            'address' => [
                'street' => 'Sesame',
                'house_number' => 21,
                'flat_number' => 5
            ],
            'book' => [
                'isbn' => 'isbn',
                'title' => 'title'
            ],
            'car' => null
        ];

        $walker = new EmbedWalker('address', 'book', 'car');

        $this->assertSame($output, $walker->walk($input));
    }
}
