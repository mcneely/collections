<?php
/**
 * Created by IntelliJ IDEA.
 * User: mcneely
 * Date: 4/17/18
 * Time: 10:44 PM
 */

namespace tests;

use mcneely\collections\Collection;
use PHPUnit\Framework\TestCase;

class CollectionTest extends TestCase
{
    protected $initialArray = [1,2,3,4,5];
    /** @var Collection */
    protected $collection;

    public function setUp()
    {
        $this->collection = new Collection($this->initialArray);
    }

    public function testCount()
    {
        $this->assertCount(5,$this->collection);
    }

    public function testToArray()
    {
        $this->assertEquals($this->initialArray, $this->collection->toArray());
    }


    public function testClear()
    {
        $collection = $this->collection;
        $collection->clear();
        $this->assertCount(0,$collection);
    }

    public function testMap()
    {
        $expectedArray = [2,3,4,5,6];
        $newCollection = $this->collection->map(function($item) {
            return $item + 1;
        });

        $this->assertNotEquals($newCollection, $this);
        $this->assertEquals($expectedArray, $newCollection->toArray());

    }

}
