<?php

namespace Track\Event;

class Event implements EventInterface
{
    protected $name;

    protected $data;

    protected $timestamp;

    public function __construct($name, array $data = null)
    {
        $this->name = $name;

        if (null === $data) {
            $data = array();
        }

        $this->data = $data;
        $this->setTimestamp(isset($data['timestamp']) ? $data['timestamp'] : time());
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @param mixed $timestamp
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;
        $this->data['timestamp'] = $timestamp;
    }

    /**
     * @return mixed
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * @param mixed $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    public function toArray()
    {
        return array_merge($this->getData(), array('name' => $this->name));
    }
}
