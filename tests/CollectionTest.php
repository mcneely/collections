<?php
/**
 * Created by IntelliJ IDEA.
 * User: mcneely
 * Date: 4/17/18
 * Time: 10:44 PM
 */

namespace tests;

use Mcneely\Collections\Collection;
use PHPUnit\Framework\TestCase;

class CollectionTest extends TestCase
{
    protected $initialArray = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10,];
    protected $shiftedArray = [2, 3, 4, 5, 6, 7, 8, 9, 10, 11];
    /** @var Collection */
    protected $collection;

    public function setUp()
    {
        $this->collection = new Collection($this->initialArray);
    }

    public function testCount()
    {
        $this->assertCount(count($this->initialArray), $this->collection);
        $this->assertEquals(count($this->initialArray), $this->collection->count());
    }

    public function testToArray()
    {
        $this->assertEquals($this->initialArray, $this->collection->toArray());
    }

    public function testMap()
    {
        $newCollection = $this->collection->map(function ($item) {
            return $item + 1;
        });

        $this->assertNotEquals($newCollection, $this);
        $this->assertEquals($this->shiftedArray, $newCollection->toArray());

    }

    public function testFirst()
    {
        $this->assertEquals(reset($this->initialArray), $this->collection->first());
    }

    public function testLast()
    {
        $this->assertEquals(end($this->initialArray), $this->collection->last());
    }

    public function testAdd()
    {
        $this->collection->add(42);
        $this->assertEquals(42, $this->collection->last());
    }

    public function testSet()
    {
        $this->collection->set('elementToBeRemoved', 'removeme');
        $this->assertTrue($this->collection->containsKey('elementToBeRemoved'));
    }

    public function testRemove()
    {
        $this->assertEquals($this->initialArray[3], $this->collection->remove(3));
        $this->assertLessThan(count($this->initialArray), count($this->collection->toArray()));
    }

    public function testRemoveElement() {
        $this->collection->set('elementToBeRemoved', 'removeme');
        $this->assertTrue($this->collection->containsKey('elementToBeRemoved'));
        $this->assertTrue($this->collection->removeElement('removeme'));
        $this->assertArrayNotHasKey('elementToBeRemoved',$this->collection);
        $this->assertFalse($this->collection->containsKey('elementToBeRemoved'));
        $this->assertFalse($this->collection->removeElement('removeme'));
    }

    public function testContainsKey()
    {
        $this->assertFalse($this->collection->containsKey(count($this->collection->toArray())));
        $this->assertTrue($this->collection->containsKey(0));
    }

    public function testClear()
    {
        $collection = $this->collection;
        $collection->clear();
        $this->assertCount(0, $collection);
        $this->assertCount(0, $collection->toArray());
        $this->assertEquals(0, count($collection));
        $this->assertEquals(0, count($collection->toArray()));
    }
}
