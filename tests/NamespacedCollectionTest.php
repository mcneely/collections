<?php
/**
 * Created by IntelliJ IDEA.
 * User: mcneely
 * Date: 4/20/18
 * Time: 12:06 AM
 */

namespace tests;

use Mcneely\Collections\NamespacedCollection;
use PHPUnit\Framework\TestCase;

class NamespacedCollectionTest extends TestCase
{

    /**
     * @var NamespacedCollection $collection
     */
    protected $collection;
    protected $expectedArray;

    public function testSetNamespaceSeparator()
    {
        $this->collection->setNamespaceSeparator('-');
        $this->assertEquals('-', $this->collection->getNamespaceSeparator());

    }

    public function setUp()
    {
        $this->expectedArray = [
            'FOO\\BAR\\BAZ'  => 'Meep',
            'FOO\\BAR\\Blip' => 'Moop',
        ];
        $this->collection    = new NamespacedCollection();
        foreach ($this->expectedArray as $key => $value) {
            $this->collection->set($key, $value);
        }
    }

    public function testClear()
    {
        $this->collection->clear();
        $this->assertCount(0, $this->collection);
    }

    public function testCollection()
    {
        $this->collection->set("FOO\\BAR\\BAZ", "Zirp");
        $this->collection->set("FOO\\BAR\\BIZ", "Miip");
        $this->collection->set("FOO\\BEEZ\\BAZ", "Muup");
        $this->assertEquals('Moop', $this->collection->get("FOO\\BAR\\Blip"));
        $this->collection->first();
        $this->collection->key();
        $this->collection->next();
        $this->collection->key();
        $this->collection->next();
        $this->collection->key();
        $this->assertEquals('FOO\\BAR\\BIZ', $this->collection->key());
        $this->assertFalse($this->collection->offsetExists("FOO\\BAR\\BOZ"));
        $this->expectException(\Exception::class);
        $this->collection->set("FOO\\BAR", "Beep");

    }

    public function testOffsetGetException()
    {
        $this->expectException(\Exception::class);
        $this->collection->get("FOO\\BAR\\BOZ");
    }

    public function testNameSpaceAlias()
    {
        $this->collection->addNamespaceAlias("FOO\\BAR\\Blip", "FOO\\BAR\\ZOOM");
        $this->assertEquals($this->collection->get("FOO\\BAR\\Blip"), $this->collection->get("FOO\\BAR\\ZOOM"));
        $this->collection->offsetUnset("FOO\\BAR\\Blip");
        $this->expectException(\Exception::class);
        $this->collection->offsetUnset("FOO\\BAR\\ZOOM");
    }

    public function testNameSpaceAliasException()
    {
        $this->expectException(\Exception::class);
        $this->collection->addNamespaceAlias("FOO\\BAR\\KABOOM", "FOO\\BAR\\ZOOM");
    }

    public function testToArray()
    {
        $array = $this->collection->toArray();
        $this->assertEquals($this->expectedArray, $array);
    }
}
