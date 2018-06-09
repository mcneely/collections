<?php
/**
 * Created by IntelliJ IDEA.
 * User: mcneely
 * Date: 4/17/18
 * Time: 9:12 PM
 */
declare(strict_types=1);

namespace Mcneely\Collections;

class Collection extends AbstractCollection
{

    public function __construct(array $array = [])
    {
        parent::__construct($array);
    }

    public function add($element)
    {
        $this->getIterator()->append($element);

        return $this;
    }

    /**
     * @param callable $callback
     * @return static
     */
    public function map(callable $callback)
    {
        $copy = new static($this->toArray());
        foreach ($copy as $key => $item) {
            $copy->set($key, $callback($item, $key));
        }

        return $copy;
    }

    public function removeElement($element)
    {
        $key = array_search($element, $this->toArray(), true);
        if ($key !== false) {
            $this->unset($key);

            return true;
        }

        return false;
    }

}