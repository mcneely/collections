<?php
/**
 * Created by IntelliJ IDEA.
 * User: mcneely
 * Date: 4/17/18
 * Time: 9:12 PM
 */
declare(strict_types=1);

namespace Mcneely\Collections;

use ArrayAccess;
use ArrayIterator;
use Ds\Collection as DsCollection;
use Ds\Traits\GenericCollection;
use Iterator;
use Mcneely\Collections\Traits\ArrayAccessible;

abstract class AbstractCollection implements Iterator, ArrayAccess, DsCollection
{
    use GenericCollection;
    use ArrayAccessible;


    public function __construct(array $array = array())
    {
        $this->setIterator(new ArrayIterator($array));
    }

    /**
     * @return int
     */
    public function count(): int
    {
        return $this->getIterator()->count();
    }

    /**
     * @return \Mcneely\Collections\AbstractCollection
     */
    public function clear()
    {
        return $this->setIterator(new ArrayIterator());
    }

    /**
     * Alias to satisfy Ds\Traits\GenericCollection requirements.
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->getIterator()->getArrayCopy();
    }

    /**
     * Change to be fluent.
     *
     * @param string $key
     * @param string $value
     * @return \Mcneely\Collections\AbstractCollection
     */
    public function set($key, $value)
    {
        return $this->offsetSet($key, $value);
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function get($key)
    {
        return $this->offsetGet($key);
    }

    /**
     * @param string $index
     * @return \Mcneely\Collections\AbstractCollection
     */
    public function unset($index)
    {
        return $this->offsetUnset($index);

    }

    /**
     * @return mixed
     */
    public function first()
    {
        return reset($this->iterator);
    }

    /**
     * @return mixed
     */
    public function last()
    {
        return end($this->iterator);
    }

    /**
     * @param mixed $key
     * @return bool
     */
    public function containsKey($key)
    {
        return $this->offsetExists($key);
    }

    /**
     * @param string $key
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

    /**
     * @return mixed
     */
    public function key()
    {
        return $this->getIterator()->key();
    }

    public function current()
    {
        return $this->getIterator()->current();
    }

    public function next()
    {
        $this->getIterator()->next();

        return $this;
    }

    public function valid()
    {
        return $this->getIterator()->valid();
    }

    public function rewind()
    {
        $this->getIterator()->rewind();

        return $this;
    }
}