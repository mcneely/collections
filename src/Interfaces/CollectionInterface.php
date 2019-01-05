<?php

declare(strict_types=1);

namespace Mcneely\Collections\Interfaces;

interface CollectionInterface extends \Iterator, \Countable
{
    /**
     * @return self
     */
    public function clear();

    /**
     * @return self
     */
    public function copy();

    /**
     * @return int
     */
    public function count(): int;

    /**
     * @return bool
     */
    public function isEmpty(): bool;

    /**
     * @return array
     */
    public function toArray(): array;

    /**
     * @param string $key
     * @param mixed  $value
     *
     * @return self
     */
    public function set(string $key, $value);

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function get(string $key);

    /**
     * @param string $index
     *
     * @return self
     */
    public function delete(string $index);

    /**
     * @return mixed
     */
    public function last();

    /**
     * @param string $key
     *
     * @return bool
     */
    public function containsKey(string $key): bool;

    /**
     * @param string $key
     *
     * @return mixed|null
     */
    public function remove(string $key);

    /**
     * @return mixed
     */
    public function first();

    /**
     * @param mixed $element
     *
     * @return self
     */
    public function add($element);

    /**
     * @param callable $callback
     *
     * @return static
     */
    public function each(callable $callback);

    /**
     * @param callable   $callback
     * @param array|null $extra
     *
     * @return array
     */
    public function map(callable $callback, ?array $extra = []): array;

    /**
     * @param mixed $element
     *
     * @return bool
     */
    public function removeElement($element): bool;
}
