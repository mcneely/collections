<?php
/**
 * Created by IntelliJ IDEA.
 * User: mcneely
 * Date: 4/17/18
 * Time: 9:12 PM
 */

namespace Mcneely\Collections;

class Collection extends AbstractCollection
{
    /**
     * @param callable $callback
     * @return static
     */
    public function map(callable $callback)
    {

        foreach ($this->toArray() as $key => $item) {
            $this->set($key, $callback($item, $key));
        }

        return $this;
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