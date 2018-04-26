<?php
/**
 * Created by IntelliJ IDEA.
 * User: mcneely
 * Date: 4/20/18
 * Time: 12:06 AM
 */

namespace tests;

use mcneely\collections\NamespaceCollection;
use PHPUnit\Framework\TestCase;

class NamespaceCollectionTest extends TestCase
{

    /**
     * @var NamespaceCollection $collection
     */
    protected $collection;

    public function testSetNamespaceSeparator()
    {
        $this->collection->setNamespaceSeparator('-');
        $this->assertEquals('-',$this->collection->getNamespaceSeparator());

    }

    public function setUp() {
        $this->collection = new NamespaceCollection();
    }

    public function testAdd()
    {
        $this->collection->add("FOO\\BAR\\BAZ", "Meep");
        $this->collection->add("FOO\\BAR\\Blip", "Moop");
        $this->collection->add("FOO\\BAR\\BAZ", "Miip");
        $this->expectException(\Exception::class);
        $this->collection->add("FOO\\BAR", "Beep");
    }
}
