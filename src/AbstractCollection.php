<?php
/**
 * Created by IntelliJ IDEA.
 * User: mcneely
 * Date: 4/17/18
 * Time: 9:12 PM.
 */

namespace Mcneely\Collections;

use ArrayAccess;
use Iterator;
use Mcneely\Collections\Interfaces\CollectionInterface;
use Mcneely\Collections\Traits\CollectionTrait;
use Mcneely\Core\Traits\ArrayAccessTrait;
use Mcneely\Core\Traits\CoreTrait;
use Mcneely\Core\Traits\CountableTrait;
use Mcneely\Core\Traits\GeneratorTrait;
use Mcneely\Core\Traits\IteratorTrait;
use Traversable;

abstract class AbstractCollection implements Iterator, ArrayAccess, CollectionInterface
{
    use CoreTrait;
    use IteratorTrait;
    use ArrayAccessTrait;
    use CollectionTrait;
    use CountableTrait;
    use GeneratorTrait;

    /**
     * AbstractCollection constructor.
     *
     * @param array|Traversable $items
     */
    public function __construct($items = [])
    {
        $this->setIterator($items);
        $this->fireEvents_CoreTrait($this, __CLASS__, __METHOD__, __TRAIT__);
    }

    /**
     * Change to be fluent.
     *
     * @param string $key
     * @param string $value
     *
     * @return \Mcneely\Collections\AbstractCollection
     */
    public function set($key, $value)
    {
        return $this->offsetSet($key, $value);
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function get($key)
    {
        return $this->offsetGet($key);
    }

    /**
     * @param string $index
     *
     * @return \Mcneely\Collections\AbstractCollection
     */
    public function delete($index)
    {
        return $this->offsetUnset($index);
    }

    /**
     * @return mixed
     */
    public function last()
    {
        $return = null;
        foreach ($this->getCoreObject_CoreTrait()->getObject() as $result) {
            $return = $result;
        }

        return $return;
    }

    /**
     * @param mixed $key
     *
     * @return bool
     */
    public function containsKey($key)
    {
        return $this->offsetExists($key);
    }

    /**
     * @param string $key
     *
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
    public function first()
    {
        return $this->rewind()
                    ->current();
    }

    public function add($element)
    {
        $this->getCoreObject_CoreTrait()
             ->getObject(false)
             ->append($element);

        return $this;
    }
}
