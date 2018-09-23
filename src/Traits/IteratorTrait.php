<?php

namespace Mcneely\Collections\Traits;

trait IteratorTrait
{
    public function key()
    {
        return $this->getCoreInnerObject()->key();
    }

    public function current()
    {
        return $this->getCoreInnerObject()->current();
    }

    public function next()
    {
        $this->getCoreInnerObject()->next();

        return $this;
    }

    public function valid()
    {
        return $this->getCoreInnerObject()->valid();
    }

    public function rewind()
    {
        $this->getCoreInnerObject()->rewind();

        return $this;
    }
}