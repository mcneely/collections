<?php

namespace Mcneely\Collections\Traits;

use Mcneely\Collections\CoreObject;

trait CoreObjectTrait
{
    /***
     * @var CoreObject
     */
    private $McNeelyCoreObject = null;

    /**
     * @return mixed
     */
    protected function getCoreInnerObject()
    {
        return $this->getCoreObject()->getObject();
    }

    /**
     * @return CoreObject
     */
    protected function getCoreObject()
    {
        return $this->McNeelyCoreObject;
    }

    /**
     * @param mixed $object
     *
     * @return $this
     */
    protected function setCoreObject($object = null)
    {
        $this->McNeelyCoreObject = new CoreObject($object);

        return $this;
    }
}
