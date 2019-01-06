<?php

namespace tests;

use Mcneely\Collections\Collection;
use PHPUnit\Framework\TestCase;

class TestIteratorAggregate implements \IteratorAggregate
{
    private $traversable;

    public function __construct(\Traversable $traversable)
    {
        $this->traversable = $traversable;
    }

    public function getIterator()
    {
        return $this->traversable;
    }
}

class CollectionTest extends TestCase
{
    protected $initialArray = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];

    protected $shiftedArray = [2, 3, 4, 5, 6, 7, 8, 9, 10, 11];

    /** @var Collection */
    protected $collection;

    public function setUp()
    {
        $this->collection = new Collection(new \ArrayIterator($this->initialArray));
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

    public function testEach()
    {
        $newCollection = $this->collection->each(
            function ($item) {
                return $item + 1;
            }
        );

        $this->assertNotEquals($newCollection->toArray(), $this->initialArray);
        $this->assertEquals($this->shiftedArray, $newCollection->toArray());
    }

    public function testMap()
    {
        $newArray = $this->collection->map(
            function ($item) {
                return $item + 1;
            }
        );

        $this->assertNotEquals($newArray, $this->initialArray);
        $this->assertEquals($this->shiftedArray, $newArray);
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

    public function testRemoveElement()
    {
        $this->collection->set('elementToBeRemoved', 'removeme');
        $this->assertTrue($this->collection->containsKey('elementToBeRemoved'));
        $this->assertTrue($this->collection->removeElement('removeme'));
        $this->assertArrayNotHasKey('elementToBeRemoved', $this->collection);
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

    public function testIsEmpty()
    {
        $collection = $this->collection;
        $collection->clear();
        $this->assertTrue($collection->isEmpty());
    }

    public function testCopy()
    {
        $copy = $this->collection->copy();
        $this->assertTrue($copy instanceof Collection);
        $this->assertTrue($copy->isEmpty());
    }

    public function testException()
    {
        $this->expectException(\UnexpectedValueException::class);
        new Collection((object) $this->initialArray);
    }

    public function testIteratorAggregate()
    {
        $collection = new Collection(new TestIteratorAggregate(new \ArrayIterator($this->initialArray)));
        $array      = $collection->toArray();
        $this->assertEquals($this->initialArray, $array);
    }

    public function testArrayObject()
    {
        $collection = new Collection(new \ArrayObject($this->initialArray));
        $array      = $collection->toArray();
        $this->assertEquals($this->initialArray, $array);
    }
}
