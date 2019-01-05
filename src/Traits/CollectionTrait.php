<?php declare(strict_types=1);

namespace Mcneely\Collections\Traits;

use ArrayAccess;
use ArrayIterator;
use Mcneely\Core\CoreObject;
use Mcneely\Core\Traits\ArrayAccessTrait;
use Mcneely\Core\Traits\IteratorTrait;
use Traversable;
use UnexpectedValueException;

/**
 * Trait CollectionTrait
 *
 * @package Mcneely\Collections\Traits
 * @method CoreObject CoreTrait_getCoreObject()
 * @method mixed fireEvents_CoreTrait($eventClassObject, $eventImmediateClass, $eventMethod, $eventTrait)
 * @method IteratorTrait rewind()
 */
trait CollectionTrait
{

    /** @var string $CollectionTrait_Class */
    protected $CollectionTrait_Class;

    /**
     * @return self
     */
    public function clear()
    {
        $this->CoreTrait_fireEvents($this, __CLASS__, __METHOD__, __TRAIT__);
        $class = new $this->CollectionTrait_Class;
        $this->setIterator($class);

        return $this;
    }

    /**
     * @param \Traversable|array $iterable
     *
     * @return self
     */
    protected function setIterator($iterable): self
    {
        if (is_array($iterable)) {
            $iterable = new ArrayIterator($iterable);
        }

        if (!($iterable instanceof Traversable)) {
            throw new UnexpectedValueException('Expected: array or Iterator as input');
        }

        if ($iterable instanceof \IteratorAggregate && !($iterable instanceof \ArrayObject)) {
            $iterable = $iterable->getIterator();
        }

        $this->CollectionTrait_Class = get_class($iterable);
        $this->CoreTrait_setCoreObject($iterable);

        return $this;
    }

    /**
     * @return self
     */
    public function copy()
    {
        $this->CoreTrait_fireEvents($this, __CLASS__, __METHOD__, __TRAIT__);

        return new static();
    }

    /**
     * @return bool
     */
    public function isEmpty(): bool
    {
        $this->CoreTrait_fireEvents($this, __CLASS__, __METHOD__, __TRAIT__);

        return empty($this->internalToArray());
    }

    /**
     * @return array
     */
    protected function internalToArray(): array
    {
        $object = $this
            ->CoreTrait_getCoreObject()
            ->unWrap(ArrayAccess::class)
        ;
        if ($object instanceof ArrayIterator) {
            return $object->getArrayCopy();
        }

        return (array)$object;
    }

    /**
     *
     * @param string $key
     * @param mixed  $value
     * @return self
     */
    public function set(string $key, $value)
    {
        $this->CoreTrait_fireEvents($this, __CLASS__, __METHOD__, __TRAIT__);

        return $this->offsetSet($key, $value);
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function get(string $key)
    {
        $this->CoreTrait_fireEvents($this, __CLASS__, __METHOD__, __TRAIT__);

        return $this->offsetGet($key);
    }

    /**
     * @return mixed
     */
    public function last()
    {
        $this->CoreTrait_fireEvents($this, __CLASS__, __METHOD__, __TRAIT__);

        $return = null;
        foreach ($this->CoreTrait_getCoreObject()->unWrap() as $result) {
            $return = $result;
        }

        return $return;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function containsKey(string $key): bool
    {
        $this->CoreTrait_fireEvents($this, __CLASS__, __METHOD__, __TRAIT__);

        return $this->offsetExists($key);
    }

    /**
     * @param string $key
     * @return mixed|null
     */
    public function remove(string $key)
    {
        $this->CoreTrait_fireEvents($this, __CLASS__, __METHOD__, __TRAIT__);

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
        $this->CoreTrait_fireEvents($this, __CLASS__, __METHOD__, __TRAIT__);

        return $this
            ->rewind()
            ->current()
            ;
    }

    /**
     * @param mixed $element
     * @return self
     */
    public function add($element)
    {
        $this->CoreTrait_fireEvents($this, __CLASS__, __METHOD__, __TRAIT__);

        $this->CoreTrait_getCoreObject()
             ->unWrap()
             ->append($element)
        ;

        return $this;
    }

    /**
     * @param callable $callback
     * @return static
     * @method CoreObject CoreTrait_getCoreObject()
     */
    public function each(callable $callback)
    {
        $this->CoreTrait_fireEvents($this, __CLASS__, __METHOD__, __TRAIT__);

        $object = $this
            ->CoreTrait_getCoreObject()
            ->unWrap(\ArrayAccess::class)
        ;

        $iterator = new \ArrayIterator([]);
        foreach ($object as $key => $value) {
            $iterator->offsetSet((string)$key, call_user_func_array($callback, [$value, (string)$key]));
        }

        $this->setIterator($iterator);

        return $this;
    }

    /**
     * @param callable   $callback
     * @param array|null $extra
     * @return array
     */
    public function map(callable $callback, ?array $extra = []): array
    {
        $this->CoreTrait_fireEvents($this, __CLASS__, __METHOD__, __TRAIT__);

        $return = [];
        foreach ($this->CoreTrait_getCoreObject()->getUnwrapped() as $key => $item) {
            $combined     = array_merge([$item, $key], $extra);
            $return[$key] = call_user_func_array($callback, $combined);
        }

        return $return;
    }

    /**
     * @param mixed $element
     * @return bool
     */
    public function removeElement($element): bool
    {
        $this->CoreTrait_fireEvents($this, __CLASS__, __METHOD__, __TRAIT__);

        $key = array_search($element, $this->toArray(), true);
        if ($key !== false) {
            $this->delete($key);

            return true;
        }

        return false;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $this->CoreTrait_fireEvents($this, __CLASS__, __METHOD__, __TRAIT__);

        return $this->internalToArray();
    }

    /**
     * @param string $key
     * @return self
     */
    public function delete(string $key)
    {
        $this->CoreTrait_fireEvents($this, __CLASS__, __METHOD__, __TRAIT__);

        return $this->offsetUnset($key);

    }

    protected function __setUp_CollectionTrait()
    {
        $this->CoreTrait_require([IteratorTrait::class, ArrayAccessTrait::class], __TRAIT__);
    }

}