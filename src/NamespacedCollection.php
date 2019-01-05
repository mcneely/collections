<?php

declare(strict_types=1);

namespace Mcneely\Collections;

use Ramsey\Uuid;

class NamespacedCollection extends AbstractCollection
{
    const globalNamespace = 'GLOBAL';

    /** @var string $namespaceSeparator */
    protected $namespaceSeparator = '\\';

    /** @var array $namespaceTable */
    protected $namespaceTable = [];

    /** @var array $uuidTable */
    protected $uuidTable = [];

    /** @var Uuid\UuidFactory $uuidFactory */
    protected $uuidFactory;

    /**
     * NamespacedCollection constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->uuidFactory = new Uuid\UuidFactory();
    }

    /**
     * @param string|array $namespace
     * @param mixed        $item
     *
     * @return NamespacedCollection
     *
     * @throws \Exception
     */
    public function offsetSet($namespace, $item): self
    {
        $uuid = $this->uuidFactory->uuid4()->toString();
        parent::offsetSet($uuid, $item);
        $this->addUuidNamespace($uuid, $namespace);

        return $this;
    }

    /**
     * @param string       $uuid
     * @param string|array $namespaceList
     *
     * @return \Mcneely\Collections\NamespacedCollection
     *
     * @throws \Exception
     */
    protected function addUuidNamespace($uuid, $namespaceList): self
    {
        $isPrimary = !is_array($namespaceList);
        /** @var array $namespaceList */
        $namespaceList          = $isPrimary ? [$namespaceList] : $namespaceList;
        $this->uuidTable[$uuid] = $isPrimary ? $namespaceList : array_merge($this->uuidTable[$uuid], $namespaceList);

        foreach ($namespaceList as $namespace) {
            $namespaceArray       = $this->getPathArray($namespace, $this->namespaceSeparator);
            $namespaceArray[0]    = (
                0 === count($namespaceArray) ||
                empty($namespaceArray[0])
            ) ? [self::globalNamespace] : $namespaceArray[0];
            $this->namespaceTable = $this->setValue($this->namespaceTable, $namespaceArray, $uuid);
        }

        return $this;
    }

    /**
     * @param array|string $path
     * @param string       $separator
     *
     * @return array
     */
    protected function getPathArray($path, string $separator): array
    {
        $path      = is_array($path) ? implode($separator, $path) : $path;
        $pathArray = explode($separator, $this->normalizeNameSpace($path));

        return is_array($pathArray) ? $pathArray : [];
    }

    /**
     * @param string $namespace
     *
     * @return string
     */
    protected function normalizeNameSpace(string $namespace): string
    {
        return strtoupper($namespace);
    }

    /**
     * @param array        $input
     * @param string|array $path
     * @param mixed        $value
     *
     * @return mixed
     *
     * @throws \Exception
     */
    protected function setValue($input, $path, $value)
    {
        $path = $this->getPathArray($path, $this->namespaceSeparator);
        $key  = array_shift($path);

        if (empty($path) && array_key_exists($key, $input)) {
            if (is_array($input[$key])) {
                throw new \Exception('Can not replace Key path with Item');
            }

            parent::offsetUnset($input[$key]);
        }

        $input[$key] = array_key_exists($key, $input) ? $input[$key] : [];
        $input[$key] = empty($path) ? $value : $this->setValue($input[$key], $path, $value);

        return $input;
    }

    /**
     * @param string $namespace
     *
     * @return mixed
     *
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

        return 1 === count($results) ? array_shift($results) : $results;
    }

    /**
     * @param string $namespace
     * @param bool   $returnBoolean
     *
     * @return mixed
     *
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
     *
     * @return mixed
     */
    protected function getValue($object, $path)
    {
        $path = $this->getPathArray($path, $this->namespaceSeparator);

        return array_reduce(
            $path,
            /**
             * @param array  $object
             * @param string $key
             *
             * @return mixed
             */
            function (array $object, string $key) {
                return $object[$key] ?? false;
            },
            $object
        );
    }

    /**
     * @param string $uuid
     * @param string $namespace
     *
     * @return mixed
     *
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
     *
     * @return \Mcneely\Collections\NamespacedCollection
     *
     * @throws \Exception
     */
    public function offsetUnset($namespace): self
    {
        $uuid = $this->checkNamespace($namespace);
        $this->checkUuid($uuid, $namespace);
        parent::offsetUnset($uuid);

        return $this;
    }

    /**
     * @param string $namespace
     *
     * @return bool
     *
     * @throws \Exception
     */
    public function offsetExists($namespace): bool
    {
        return $this->checkNamespace($namespace, true);
    }

    /**
     * @param string       $namespace
     * @param string|array $aliasList
     *
     * @return \Mcneely\Collections\NamespacedCollection
     *
     * @throws \Exception
     */
    public function addNamespaceAlias($namespace, $aliasList): self
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
    public function getNamespaceSeparator(): string
    {
        return $this->namespaceSeparator;
    }

    /**
     * @param string $namespaceSeparator
     *
     * @return NamespacedCollection
     */
    public function setNamespaceSeparator($namespaceSeparator): self
    {
        $this->namespaceSeparator = $namespaceSeparator;

        return $this;
    }

    public function key(): string
    {
        return $this->uuidTable[parent::key()][0];
    }

    /**
     * @return self
     */
    public function clear()
    {
        parent::clear();
        $this->namespaceTable = [];
        $this->uuidTable      = [];

        return $this;
    }

    public function toArray(): array
    {
        $result = [];
        foreach (parent::toArray() as $key => $value) {
            $result[$this->uuidTable[$key][0]] = $value;
        }

        return $result;
    }
}
