<?php
/**
 * Created by PhpStorm.
 * User: mcneely
 * Date: 1/5/19
 * Time: 10:33 AM
 */

namespace tests\Traits;

use Mcneely\Collections\Traits\LazyLoadingTrait;
use Mcneely\Core\Traits\CoreTrait;
use PHPUnit\Framework\TestCase;

class LazyLoadingTraitTest extends TestCase
{
    use CoreTrait;
    use LazyLoadingTrait;

    private $initializeFired = false;

    public function testIsInitialized()
    {
        $this->assertFalse($this->isInitialized());
    }

    public function testDoInitialize()
    {
        $this->CoreTrait_fireEvents($this, __CLASS__, __METHOD__, __TRAIT__);
        $this->assertTrue($this->isInitialized());
        $this->assertTrue($this->initializeFired);
        $this->initializeFired = false;
        $this->LazyLoadingTrait_doInitialize();
        $this->assertTrue($this->isInitialized());
        $this->assertFalse($this->initializeFired);
    }

    private function initialize()
    {
        $this->initializeFired = true;
    }

}
