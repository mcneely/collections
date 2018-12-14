<?php
/**
 * Created by IntelliJ IDEA.
 * User: mcneely
 * Date: 9/29/18
 * Time: 8:15 PM
 */

namespace Mcneely\Collections\Traits;

use Mcneely\Core\CoreObject;

/**
 * Trait LazyLoadingTrait
 *
 * @package Mcneely\Collections\Traits
 * @method void LazyLoadingInitialize()
 * @method CoreObject getCoreObject_CoreTrait()
 * @method mixed fireEvents_CoreTrait($eventClassObject, $eventImmediateClass, $eventMethod, $eventTrait)
 */
trait LazyLoadingTrait
{
    private $LazyLoadingTrait_Initialized = false;

    public function isInitialized()
    {
        return $this->LazyLoadingTrait_Initialized;
    }

    private function toggleInitialized()
    {
        $this->LazyLoadingTrait_Initialized = $this->LazyLoadingTrait_Initialized ? false : true;

        return $this;
    }

    public function __setUp_LazyLoadingTrait()
    {
        $this->registerEvent_CoreTrait('*', 'initialize', $exclude = ['__construct']);
    }

    /**
     * @return mixed $this
     */
    public function initialize()
    {
        if ($this->isInitialized() || !method_exists($this, 'LazyLoadingInitialize')) {
            return $this;
        }

        $this->toggleInitialized()
             ->LazyLoadingInitialize();

        return $this;
    }
}