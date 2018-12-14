<?php
/**
 * Created by IntelliJ IDEA.
 * User: mcneely
 * Date: 4/20/18
 * Time: 12:06 AM
 */

namespace tests;

use mcneely\collections\NamespacedCollection;
use PHPUnit\Framework\TestCase;

class NamespaceCollectionTest extends TestCase
{

    /**
     * @var NamespacedCollection $collection
     */
    protected $collection;

    public function testSetNamespaceSeparator()
    {
        $this->collection->setNamespaceSeparator('-');
        $this->assertEquals('-',$this->collection->getNamespaceSeparator());

    }

    public function setUp() {
        $this->collection = new NamespacedCollection();
    }

    public function testAdd()
    {
        $this->collection->set("FOO\\BAR\\BAZ", "Meep");
        $this->collection->set("FOO\\BAR\\Blip", "Moop");
        $this->collection->set("FOO\\BAR\\BAZ", "Miip");
        $this->collection->set("FOO\\BEEZ\\BAZ", "Muup");
        $this->expectException(\Exception::class);
        $this->collection->set("FOO\\BAR", "Beep");
    }
}
