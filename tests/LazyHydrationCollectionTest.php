<?php

namespace tests;

use Mcneely\Collections\LazyHydrationCollection;
use PHPUnit\Framework\TestCase;

class Test
{
    /** @var int $value */
    private $value = null;

    /**
     * @return int
     */
    public function getValue(): ?int
    {
        return $this->value;
    }

    /**
     * @param int $value
     *
     * @return Test
     */
    public function setValue(int $value): Test
    {
        $this->value = $value;

        return $this;
    }
}

class LazyHydrationCollectionTest extends TestCase
{
    protected $initialArray = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];

    /** @var LazyHydrationCollection $collection */
    protected $collection;

    public function setUp()
    {
        $this->collection = new LazyHydrationCollection($this->initialArray);
        $this->collection->setClass(Test::class);
    }

    public function testSetHydrator()
    {
        $hydrator = function ($data, $class) {
            /* @var Test $class */
            return $class->setValue($data);
        };

        $this->collection->setHydrator($hydrator);

        $array = $this->collection->map(
            function ($object) {
                /* @var Test $object */
                return $object->getValue();
            }
        );

        $this->assertEquals($this->initialArray, $array);
    }

    public function testSetHydratorGenerator()
    {
        $hydrator = function ($data, $class) {
            /* @var Test $class */
            return $class->setValue($data);
        };

        $this->collection->setHydrator($hydrator, true);

        $array = $this->collection->map(
            function ($object) {
                /* @var Test $object */
                return $object->getValue();
            }
        );

        $this->assertEquals($this->initialArray, $array);
    }
}
