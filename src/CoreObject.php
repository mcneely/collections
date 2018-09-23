<?php

namespace Mcneely\Collections;

class CoreObject
{
    /**
     * @var mixed
     */
    protected $object;

    public function __construct($object)
    {
        $this->object = $object;
    }

    /**
     * @return mixed
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * @param string $instance
     * @return boolean
     */
    public function isInstanceOf($instance)
    {
        return ($this->object instanceof $instance);
    }

    /**
     * @param string $method
     * @return boolean
     */
    public function hasMethod($method)
    {
        return method_exists($this->object, $method);
    }
}