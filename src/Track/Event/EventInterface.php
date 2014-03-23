<?php

namespace Track\Event;

interface EventInterface
{
    public function getName();

    public function setName($name);

    public function getTimestamp();

    public function setTimestamp($timestamp);

    public function getData();

    public function setData($data);

    public function toArray();
}
