<?php declare(strict_types=1);

namespace Mcneely\Collections\Traits;

use Mcneely\Core\CoreObject;

/**
 * Trait LazyLoadingTrait.
 *
 * @package Mcneely\Collections\Traits
 *
 * @method void initialize()
 * @method CoreObject getCoreObject_CoreTrait()
 * @method mixed fireEvents_CoreTrait($eventClassObject, $eventImmediateClass, $eventMethod, $eventTrait)
 * @method self CoreTrait_registerEvent($onFunction, $triggerMethod, array $exclude)
 */
trait LazyLoadingTrait
{
    /** @var bool $LazyLoadingTrait_Initialized */
    private $LazyLoadingTrait_Initialized = false;

    public function __setUp_LazyLoadingTrait()
    {
        $this->CoreTrait_registerEvent('*', 'LazyLoadingTrait_doInitialize', $exclude = ['__construct']);
    }

    /**
     * @return mixed $this
     */
    public function LazyLoadingTrait_doInitialize()
    {
        if ($this->isInitialized() || !method_exists($this, 'initialize')) {
            return $this;
        }

        $this
            ->toggleInitialized()
            ->initialize()
        ;

        return $this;
    }

    /**
     * @return bool
     */
    public function isInitialized(): bool
    {
        return $this->LazyLoadingTrait_Initialized;
    }

    /**
     * @return self
     */
    private function toggleInitialized(): self
    {
        $this->LazyLoadingTrait_Initialized = $this->LazyLoadingTrait_Initialized ? false : true;

        return $this;
    }
}
