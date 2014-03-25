<?php

namespace Track\Event;

use RandomLib\Factory;

class Event implements EventInterface
{
    const DEFAULT_RANDOM_ID_LENGTH = 256;

    protected $name;

    protected $data;

    protected $id;

    protected $timestamp;

    public function __construct($name, array $data = null)
    {
        $this->name = $name;

        if (null === $data) {
            $data = array();
        }

        $this->data = $data;
        $this->setTimestamp(isset($data['timestamp']) ? $data['timestamp'] : time());
        $this->setId(isset($data['id']) ? $data['id'] : $this->generateRandomId());
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
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
        $this->data['id'] = $id;
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

    protected function generateRandomId()
    {
        $factory = new Factory();
        $generator = $factory->getLowStrengthGenerator();

        return $generator->generateString(self::DEFAULT_RANDOM_ID_LENGTH);
    }
}
