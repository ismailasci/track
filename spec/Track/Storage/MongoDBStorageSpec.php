<?php

namespace spec\Track\Storage;

use PhpSpec\Exception\Example\PendingException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Track\Storage\MongoDBStorage;

class MongoDBStorageSpec extends ObjectBehavior
{
    /**
     * @param \MongoDB $mongodb
     */
    function let($mongodb)
    {
        $this->beConstructedWith($mongodb);
    }

    function it_should_be_storage()
    {
        $this->shouldImplement('Track\Storage\StorageInterface');
    }

    /**
     * @param \MongoDB $mongodb
     * @param \MongoCollection $collection
     */
    function it_should_store_event($mongodb, $collection)
    {
        $mongodb->selectCollection(MongoDBStorage::MONGODB_EVENT_COLLECTION)
            ->shouldBeCalled()
            ->willReturn($collection)
        ;
        $collection->insert(array('name' => 'Page View'))->shouldBeCalled();

        $this->save(array('name' => 'Page View'));
    }

    /**
     * @param \MongoDB $mongodb
     * @param \MongoCollection $collection
     * @param \MongoCursor $cursor
     */
    function it_should_run_native_query_and_return_empty_array($mongodb, $collection, $cursor)
    {
        $query = array('name' => 'Page View');

        $mongodb->selectCollection(MongoDBStorage::MONGODB_EVENT_COLLECTION)
            ->shouldBeCalled()
            ->willReturn($collection)
        ;

        $collection->find($query)->shouldBeCalled()->willReturn($cursor);

        $this->runNativeQuery($query, null)->shouldReturn(array());
    }
}
