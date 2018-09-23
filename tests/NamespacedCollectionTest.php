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

    public function testSetNamespaceSeparator()
    {
        $this->collection->setNamespaceSeparator('-');
        $this->assertEquals('-', $this->collection->getNamespaceSeparator());

    }

    public function setUp()
    {
        $this->collection = new NamespacedCollection();
    }

    public function testCollection()
    {
        $this->collection->set("FOO\\BAR\\BAZ", "Meep");
        $this->collection->set("FOO\\BAR\\Blip", "Moop");
        $this->collection->clear();
        $this->assertCount(0, $this->collection);
        $this->collection->set("FOO\\BAR\\BAZ", "Meep");
        $this->collection->set("FOO\\BAR\\Blip", "Moop");
        $this->collection->set("FOO\\BAR\\BIZ", "Miip");
        $this->collection->set("FOO\\BEEZ\\BAZ", "Muup");
        $this->assertEquals('Moop', $this->collection->get("FOO\\BAR\\Blip"));
        $this->collection->first();
        $this->collection->next();
        $this->collection->next();
        $this->assertEquals('FOO\\BAR\\BIZ', $this->collection->key());
        $threwException = false;
        try {
            $this->collection->set("FOO\\BAR", "Beep");
        } catch (\Exception $e) {
            $threwException = true;
        }
        $this->assertTrue($threwException);
    }
}
