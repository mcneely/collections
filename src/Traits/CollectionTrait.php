<?php

namespace Mcneely\Collections\Traits;

use ArrayIterator;
use Mcneely\Core\CoreObject;
use Traversable;
use UnexpectedValueException;

/**
 * Trait CollectionTrait.
 *
 * @package Mcneely\Collections\Traits
 *
 * @method CoreObject getCoreObject_CoreTrait()
 * @method mixed fireEvents_CoreTrait($eventClassObject, $eventImmediateClass, $eventMethod, $eventTrait)
 */
trait CollectionTrait
{
    public function clear()
    {
        $this->fireEvents_CoreTrait($this, __CLASS__, __METHOD__, __TRAIT__);

        $class = $this->getCoreObject_CoreTrait()->getClass();
        $this->setIterator(new $class());

        return $this;
    }

    /**
     * @return mixed
     */
    public function copy()
    {
        $this->fireEvents_CoreTrait($this, __CLASS__, __METHOD__, __TRAIT__);

        return new static();
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
        $this->fireEvents_CoreTrait($this, __CLASS__, __METHOD__, __TRAIT__);

        return empty($this->getCoreObject_CoreTrait()->getObject());
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $this->fireEvents_CoreTrait($this, __CLASS__, __METHOD__, __TRAIT__);

        return iterator_to_array($this->getCoreObject_CoreTrait()->getObject());
    }

    /**
     * @return array
     */
    public function __debugInfo()
    {
        return $this->toArray();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $this->fireEvents_CoreTrait($this, __CLASS__, __METHOD__, __TRAIT__);

        return 'object('.get_class($this).')';
    }

    /**
     * @param \Traversable|array $iterable
     *
     * @return $this
     */
    protected function setIterator($iterable)
    {
        if (is_array($iterable)) {
            $iterable = new ArrayIterator($iterable);
        }

        if (!($iterable instanceof Traversable)) {
            throw new UnexpectedValueException('Expected: array or Iterator as input');
        }

        if ($iterable instanceof \IteratorAggregate) {
            $iterable = $iterable->getIterator();
        }

        $this->setCoreObject_CoreTrait($iterable);

        return $this;
    }
}
