<?php
/**
 * Created by IntelliJ IDEA.
 * User: mcneely
 * Date: 4/19/18
 * Time: 5:35 PM
 */

namespace Mcneely\Collections;

use Ramsey\Uuid;

class NamespacedCollection extends AbstractCollection
{
    const globalNamespace = "GLOBAL";

    protected $namespaceSeparator = '\\';

    protected $namespaceTable = [];

    protected $uuidTable = [];

    protected $uuidFactory;

    /**
     * NamespacedCollection constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->uuidFactory = new Uuid\UuidFactory();
    }

    /**
     * @param string|array $namespace
     * @param  mixed       $item
     * @return NamespacedCollection
     * @throws \Exception
     */
    public function offsetSet($namespace, $item)
    {
        $uuid = $this->uuidFactory->uuid4()->toString();
        parent::offsetSet($uuid, $item);
        $this->addUuidNamespace($uuid, $namespace);

        return $this;
    }

    /**
     * @param  string      $uuid
     * @param string|array $namespaceList
     * @return \Mcneely\Collections\NamespacedCollection
     * @throws \Exception
     */
    protected function addUuidNamespace($uuid, $namespaceList)
    {
        $isPrimary = !is_array($namespaceList);
        /** @var array $namespaceList */
        $namespaceList          = $isPrimary ? [$namespaceList] : $namespaceList;
        $this->uuidTable[$uuid] = $isPrimary ? $namespaceList : array_merge($this->uuidTable[$uuid], $namespaceList);

        foreach ($namespaceList as $namespace) {
            $namespaceArray       = $this->getPathArray($namespace, $this->namespaceSeparator);
            $namespaceArray[0]    = (
                count($namespaceArray) === 0 ||
                empty($namespaceArray[0])
            ) ? [self::globalNamespace] : $namespaceArray[0];
            $this->namespaceTable = $this->setValue($this->namespaceTable, $namespaceArray, $uuid);
        }

        return $this;
    }

    protected function getPathArray($path, $separator)
    {
        $path = is_array($path) ? implode($separator, $path) : $path;

        return explode($separator, $this->normalizeNameSpace($path));
    }

    protected function normalizeNameSpace($namespace)
    {
        return strtoupper($namespace);
    }

    /**
     * @param array        $input
     * @param string|array $path
     * @param mixed        $value
     * @return mixed
     * @throws \Exception
     */
    protected function setValue($input, $path, $value)
    {
        $path = $this->getPathArray($path, $this->namespaceSeparator);
        $key  = array_shift($path);

        if (empty($path) && array_key_exists($key, $input)) {
            if (is_array($input[$key])) {
                throw new \Exception("Can not replace Key path with Item");
            }

            parent::offsetUnset($input[$key]);
        }

        $input[$key] = array_key_exists($key, $input) ? $input[$key] : [];
        $input[$key] = empty($path) ? $value : $this->setValue($input[$key], $path, $value);

        return $input;
    }

    /**
     * @param string $namespace
     * @return mixed
     * @throws \Exception
     */
    public function offsetGet($namespace)
    {
        $result  = $this->checkNamespace($namespace);
        $results = is_array($result) ? $result : [$result];

        foreach ($results as $key => $result) {
            $subKey        = $namespace.$this->namespaceSeparator.$key;
            $results[$key] = is_array($result) ? $this->offsetGet($subKey) : $this->checkUuid($result, $namespace);
        }

        return count($results) === 1 ? array_shift($results) : $results;
    }

    /**
     * @param string $namespace
     * @param bool   $returnBoolean
     * @return mixed
     * @throws \Exception
     */
    protected function checkNamespace($namespace, $returnBoolean = false)
    {
        $result = $this->getValue($this->namespaceTable, $namespace);
        if (!$result) {
            if ($returnBoolean) {
                return false;
            } else {
                throw new \Exception("$namespace does not exist.");
            }
        }

        return ($returnBoolean) ? true : $result;
    }

    /**
     * @param array  $object
     * @param string $path
     * @return mixed
     */
    protected function getValue($object, $path)
    {
        $path = $this->getPathArray($path, $this->namespaceSeparator);

        return array_reduce(
            $path,
            function ($object, $key) {
                return $object[$key] ?: false;
            },
            $object
        );
    }

    /**
     * @param string $uuid
     * @param string $namespace
     * @return mixed
     * @throws \Exception
     */
    protected function checkUuid($uuid, $namespace)
    {
        if (!$uuid || !parent::offsetExists($uuid)) {
            throw new \Exception("$namespace does not exist.");
        }

        return parent::offsetGet($uuid);
    }

    /**
     * @param string $namespace
     * @return \Mcneely\Collections\NamespacedCollection
     * @throws \Exception
     */
    public function offsetUnset($namespace)
    {
        $uuid = $this->checkNamespace($namespace);
        $this->checkUuid($uuid, $namespace);
        parent::offsetUnset($uuid);

        return $this;
    }

    /**
     * @param string $namespace
     * @return bool|mixed
     * @throws \Exception
     */
    public function offsetExists($namespace)
    {
        return $this->checkNamespace($namespace, true);
    }

    /**
     * @param string       $namespace
     * @param string|array $aliasList
     * @return \Mcneely\Collections\NamespacedCollection
     * @throws \Exception
     */
    public function addNamespaceAlias($namespace, $aliasList)
    {
        $uuid = $this->getValue($this->namespaceTable, $namespace);
        if (!$uuid) {
            throw new \Exception("$namespace does not exist.");
        }
        $aliasList = is_array($aliasList) ? $aliasList : [$aliasList];
        $this->addUuidNamespace($uuid, $aliasList);

        return $this;
    }

    /**
     * @return string
     */
    public function getNamespaceSeparator()
    {
        return $this->namespaceSeparator;
    }

    /**
     * @param string $namespaceSeparator
     * @return NamespacedCollection
     */
    public function setNamespaceSeparator($namespaceSeparator)
    {
        $this->namespaceSeparator = $namespaceSeparator;

        return $this;
    }

    public function key()
    {
        $key = parent::key();
        $table = $this->uuidTable;
        return $this->uuidTable[parent::key()][0];
    }

    /**
     * @return $this|\Mcneely\Collections\AbstractCollection
     */
    public function clear()
    {
        parent::clear();
        $this->namespaceTable = [];
        $this->uuidTable      = [];

        return $this;
    }

    public function toArray()
    {
        $result = [];
        foreach (parent::toArray() as $key => $value) {
            $result[] = $this->uuidTable[$key][0];
        }

        return $result;
    }


}
