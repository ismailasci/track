<?php

namespace spec\Track;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ClientSpec extends ObjectBehavior
{
    /**
     * @param \Track\Storage\StorageInterface $storage
     */
    function let($storage)
    {
        $this->beConstructedWith($storage);
    }

    /**
     * @param \Track\Storage\StorageInterface $storage
     */
    function its_storage_has_initial_value($storage)
    {
        $this->getStorage()->shouldReturn($storage);
    }

    /**
     * @param \Track\Storage\MongoDBStorage $mongoStorage
     */
    function its_storage_is_mutable($mongoStorage)
    {
        $this->setStorage($mongoStorage);
        $this->getStorage()->shouldReturn($mongoStorage);
    }

    /**
     * @param \Track\Event\EventInterface $event
     * @param \Track\Storage\StorageInterface $storage
     */
    function its_should_track_event($event, $storage)
    {
        $event->toArray()->shouldBeCalled()->willReturn(array());
        $storage->save(array())->shouldBeCalled();

        $this->track($event);
    }
}
