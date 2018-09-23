<?php

namespace Mcneely\Collections\Traits;

use ArrayIterator;
use Iterator;
use UnexpectedValueException;

trait CollectionTrait
{
    /**
     * @return mixed
     */
    public function copy()
    {
        return new static();
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return empty($this->getCoreInnerObject());
    }

    /**
     * @return mixed
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return iterator_to_array($this->getCoreInnerObject());
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
        return 'object('.get_class($this).')';
    }

    public function clear()
    {
        $this->setIterator(new ArrayIterator([]));

        return $this;
    }

    /**
     * @param \Iterator|array $iterable
     * @return $this
     */
    public function setIterator($iterable)
    {
        if (is_array($iterable)) {
            $iterable = new ArrayIterator($iterable);
        }

        if (!($iterable instanceof Iterator)) {
            throw new UnexpectedValueException('Expected: array or Iterator as input');
        }

        $this->setCoreObject($iterable);

        return $this;
    }
}