<?php declare(strict_types=1);

namespace Mcneely\Collections\Interfaces;


interface CollectionInterface extends \Iterator, \Countable
{
    /**
     * @return self
     */
    function clear();

    /**
     * @return self
     */
    function copy();

    /**
     * @return integer
     */
    function count(): int;

    /**
     * @return boolean
     */
    function isEmpty(): bool;

    /**
     * @return array
     */
    function toArray(): array;

    /**
     * @param string $key
     * @param mixed  $value
     * @return self
     */
    public function set(string $key, $value);

    /**
     * @param string $key
     * @return mixed
     */
    public function get(string $key);

    /**
     * @param string $index
     * @return self
     */
    public function delete(string $index);

    /**
     * @return mixed
     */
    public function last();

    /**
     * @param string $key
     * @return bool
     */
    public function containsKey(string $key): bool;

    /**
     * @param string $key
     * @return mixed|null
     */
    public function remove(string $key);

    /**
     * @return mixed
     */
    public function first();

    /**
     * @param mixed $element
     * @return self
     */
    public function add($element);

    /**
     * @param callable $callback
     * @return static
     */
    public function each(callable $callback);

    /**
     * @param callable   $callback
     * @param array|null $extra
     * @return array
     */
    public function map(callable $callback, ?array $extra = []): array;

    /**
     * @param mixed $element
     * @return bool
     */
    public function removeElement($element): bool;
}