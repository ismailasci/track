<?php

namespace Track;

use Track\Storage\StorageInterface;

class Query
{
    protected $storage;

    public function __construct(StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    public function native(array $query, callable $callback = null)
    {
        return $this->storage->runNativeQuery($query, $callback);
    }

    /**
     * @param \Track\Storage\StorageInterface $storage
     */
    public function setStorage($storage)
    {
        $this->storage = $storage;
    }

    /**
     * @return \Track\Storage\StorageInterface
     */
    public function getStorage()
    {
        return $this->storage;
    }
}
