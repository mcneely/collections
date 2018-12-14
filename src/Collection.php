<?php
/**
 * Created by IntelliJ IDEA.
 * User: mcneely
 * Date: 4/17/18
 * Time: 9:12 PM
 */

namespace Mcneely\Collections;
use Mcneely\Collections\Traits\LazyLoadingTrait;
class Collection extends AbstractCollection
{
    use LazyLoadingTrait;
    /**
     * @param callable $callback
     * @return static
     */
    public function each(callable $callback)
    {
        $object = $this->getCoreObject_CoreTrait()->getObject();

        $iterator = new \ArrayIterator([]);
        foreach ($object as $key => $value) {
            $iterator->offsetSet($key, $callback($value, $key));
        }

        $this->setIterator($iterator);

        return $this;
    }

    public function map(callable  $callback, $extra) {
        $return = [];
        foreach ($this->getCoreObject_CoreTrait()->getObject() as $key => $item) {
            $combined = array_merge([$item, $key], $extra);
            $return = call_user_func_array($callback, $combined);
        }

        return $return;
    }

    public function removeElement($element)
    {
        $key = array_search($element, $this->toArray(), true);
        if ($key !== false) {
            $this->delete($key);

            return true;
        }

        return false;
    }

}