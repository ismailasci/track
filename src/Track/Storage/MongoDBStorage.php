<?php

namespace Track\Storage;

use \MongoDB;

class MongoDBStorage implements StorageInterface
{
    const MONGODB_EVENT_COLLECTION = 'events';

    protected $mongodb;

    public function __construct(MongoDB $mongodb)
    {
        $this->mongodb = $mongodb;
    }

    public function save(array $data)
    {
        $this->mongodb
            ->selectCollection(self::MONGODB_EVENT_COLLECTION)
            ->insert($data)
        ;
    }

    public function runNativeQuery(array $query, $callback = null)
    {
        $data = $this->mongodb
            ->selectCollection(self::MONGODB_EVENT_COLLECTION)
            ->find($query);

        $data = iterator_to_array($data);

        if (is_callable($callback)) {
            $data = array_map($callback, $data);
        }

        return $data;
    }
}
