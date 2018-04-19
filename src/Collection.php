<?php
/**
 * Created by IntelliJ IDEA.
 * User: mcneely
 * Date: 4/17/18
 * Time: 9:12 PM
 */
declare(strict_types=1);

namespace mcneely\collections;

use \ArrayIterator;
use Ds\Collection as DsCollection;
use Ds\Traits\GenericCollection;

class Collection extends ArrayIterator implements DsCollection
{
    use GenericCollection;

    public function __construct(array $array = array(), int $flags = 0)
    {
        parent::__construct($array, $flags);
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return (int)parent::count();
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return $this->getArrayCopy();
    }

    /**
     * @return $this
     */
    public function clear()
    {
        $this->__construct([], (int)$this->getFlags());

        return $this;
    }

    /**
     * @param callable $callback
     * @return static
     */
    public function map(callable $callback)
    {
        $copy = new static($this->getArrayCopy());
        foreach ($copy as $key => $item) {
            $copy->offsetSet($key, $callback($item, $key));
        }

        return $copy;
    }

    /**
     * @return mixed
     */
    public function first()
    {
        return reset($this);
    }

    /**
     * @return mixed
     */
    public function last()
    {
        return end($this);
    }

    /**
     * @param $key
     * @return mixed|null
     */
    public function remove($key)
    {
        $deleted = null;
        if ($this->offsetExists($key)) {
            $deleted = $this->offsetGet($key);
            $this->offsetUnset($key);
        }

        return $deleted;
    }

    public function removeElement($element)
    {
        $key = array_search($element, $this->getArrayCopy(), true);
        if ($key !== false) {
            $this->offsetUnset($key);
            return true;
        }

        return true;
    }

    public function containsKey($key)
    {
        return $this->offsetExists($key);
    }

}