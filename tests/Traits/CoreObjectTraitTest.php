<?php
/**
 * Created by IntelliJ IDEA.
 * User: mcneely
 * Date: 9/22/18
 * Time: 9:52 PM
 */

namespace tests\Traits;

use Mcneely\Collections\Traits\CoreObjectTrait;
use PHPUnit\Framework\TestCase;

class CoreObjectTraitTest extends TestCase
{
    use CoreObjectTrait;
    protected $object;

    public function setUp()
    {
        $this->object = new \ArrayObject([]);
        $this->setCoreObject($this->object);
    }

    public function testGetCoreInnerObject()
    {
        $this->assertEquals($this->object, $this->getCoreInnerObject());
    }

    public function testIsInstanceOf()
    {
        $this->assertTrue($this->getCoreObject()->isInstanceOf(get_class($this->object)));
        $this->assertFalse($this->getCoreObject()->isInstanceOf('string'));
    }

    public function testHasMethod()
    {
        $this->assertTrue($this->getCoreObject()->hasMethod('__construct'));
        $this->assertFalse($this->getCoreObject()->hasMethod('NoThisIsNotARealMethod'));
    }
}