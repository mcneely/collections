<?php

namespace Mcneely\Collections\Traits;

/**
 * Trait ArrayAccessTrait
 *
 * @package Mcneely\Collections\Traits
 */
trait ArrayAccessTrait
{
    public function offsetExists($offset)
    {
        return $this->getCoreInnerObject()->offsetExists($offset);
    }

    public function offsetGet($offset)
    {
        return $this->getCoreInnerObject()->offsetGet($offset);
    }

    public function offsetSet($offset, $value)
    {
        $this->getCoreInnerObject()->offsetSet($offset, $value);

        return $this;
    }

    public function offsetUnset($offset)
    {
        $this->getCoreInnerObject()->offsetUnset($offset);

        return $this;
    }
}