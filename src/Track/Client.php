<?php

namespace Track;

use Track\Event\EventInterface;
use Track\Storage\StorageInterface;

class Client
{
    protected $storage;

    public function __construct(StorageInterface $storage)
    {
        $this->storage = $storage;
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

    public function track(EventInterface $event)
    {
        $this->storage->save($event->toArray());
    }
}
