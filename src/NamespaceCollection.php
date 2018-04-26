<?php
/**
 * Created by IntelliJ IDEA.
 * User: mcneely
 * Date: 4/19/18
 * Time: 5:35 PM
 */
declare(strict_types=1);

namespace mcneely\collections;

use Ramsey\Uuid;

class NamespaceCollection
{
    const globalNamespace = "GLOBAL";

    protected $namespaceSeparator = '\\';

    /**
     * @var Collection
     */
    protected $collection;

    protected $namespaceMap = [];

    protected $uuidFactory;

    public function __construct()
    {
        $this->collection  = new Collection([self::globalNamespace => []]);
        $this->uuidFactory = new Uuid\UuidFactory();
    }

    /**
     * @param $namespace
     * @param $item
     * @return NamespaceCollection
     * @throws \Exception
     */
    public function add(string $namespace, $item): NamespaceCollection
    {
        $namespaceArray     = explode($this->namespaceSeparator, $namespace);
        $namespaceArray     = (count($namespaceArray) == 0) ? [self::globalNamespace] : $namespaceArray;
        $uuid               = $this->uuidFactory->uuid4()->toString();
        $this->namespaceMap = $this->recursiveSetValue($this->namespaceMap, $namespaceArray, $uuid);
        $this->collection->set($uuid, $item);

        return $this;
    }

    /**
     * @param $input
     * @param $path
     * @param $value
     * @return mixed
     * @throws \Exception
     */
    protected function recursiveSetValue($input, $path, $value)
    {
        $key = array_shift($path);

        if (empty($path) && array_key_exists($key, $input)) {
            if (is_array($input[$key])) {
                throw new \Exception("Can not replace Key path with Item");
            }

            $this->collection->remove($input[$key]);
        }

        $input[$key] = array_key_exists($key, $input) ? $input[$key] : [];
        $input[$key] = empty($path) ? $value : $this->recursiveSetValue($input[$key], $path, $value);


        return $input;
    }

    /**
     * @param string $namespaceSeparator
     * @return NamespaceCollection
     */
    public function setNamespaceSeparator(string $namespaceSeparator): NamespaceCollection
    {
        $this->namespaceSeparator = $namespaceSeparator;

        return $this;
    }

    /**
     * @return string
     */
    public function getNamespaceSeparator(): string
    {
        return $this->namespaceSeparator;
    }
}