<?php

namespace Mcneely\Collections\Traits;

/**
 * Trait CountableTrait
 *
 * @package Mcneely\Collections\Traits
 * @method \Mcneely\Collections\CoreObject getCoreObject()
 */
trait CountableTrait
{
    /**
     * @return int
     */
    public function count()
    {
        $object = $this->getCoreInnerObject();

        if ($this->getCoreObject()->hasMethod('count')) {
            return $object->count();
        }

        if ($this->getCoreObject()->isInstanceOf("\Traversable")) {
            return iterator_count($object);
        }

        return count($object);
    }
}