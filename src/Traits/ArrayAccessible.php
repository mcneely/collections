<?php
/**
 * Created by IntelliJ IDEA.
 * User: mcneely
 * Date: 6/9/18
 * Time: 2:32 PM
 */

namespace Mcneely\Collections\Traits;

trait ArrayAccessible
{
    /**
     * @var \ArrayIterator $iterator
     */
    protected $iterator;

    public function offsetExists($offset)
    {
        return $this->getIterator()->offsetExists($offset);
    }

    public function offsetGet($offset)
    {
        return $this->getIterator()->offsetGet($offset);
    }


    public function offsetSet($offset, $value)
    {
        $this->getIterator()->offsetSet($offset, $value);

        return $this;
    }


    public function offsetUnset($offset)
    {
        $this->getIterator()->offsetUnset($offset);

        return $this;
    }

    public function getIterator()
    {
        return $this->iterator;
    }

    /**
     * @param \ArrayIterator $iterator
     * @return $this
     */
    public function setIterator($iterator)
    {
        $this->iterator = $iterator;

        return $this;
    }
}