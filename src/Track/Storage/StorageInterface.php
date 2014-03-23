<?php

namespace Track\Storage;

interface StorageInterface
{
    public function save(array $data);

    public function runNativeQuery(array $query, callable $callback = null);
}
