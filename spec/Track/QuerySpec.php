<?php

namespace spec\Track;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class QuerySpec extends ObjectBehavior
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
     * @param \Track\Storage\StorageInterface $storage
     */
    function its_should_run_native_query($storage)
    {
        $storage->runNativeQuery(array(), null)->shouldBeCalled()->willReturn(array());
        $this->native(array(), null)->shouldReturn(array());
    }
}
