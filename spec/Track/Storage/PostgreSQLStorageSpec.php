<?php

namespace spec\Track\Storage;

use PhpSpec\Exception\Example\PendingException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class PostgreSQLStorageSpec extends ObjectBehavior
{
    /**
     * @param \PDO $pdo
     */
    function let($pdo)
    {
        $this->beConstructedWith($pdo);
    }

    function it_should_be_storage()
    {
        $this->shouldImplement('Track\Storage\StorageInterface');
    }

    /**
     * @param \PDO $pdo
     * @param \PDOStatement $pdoStatement
     */
    function it_should_store_event($pdo, $pdoStatement)
    {
        $hstoreString = '"ip"=>"127.0.0.1","name"=>"Page View"';

        $pdo->prepare('INSERT INTO events (data) VALUES (:data)')->shouldBeCalled()->willReturn($pdoStatement);
        $pdoStatement->bindValue(':data', $hstoreString, \PDO::PARAM_STR)->shouldBeCalled();
        $pdoStatement->execute()->shouldBeCalled();

        $this->save(array('ip' => '127.0.0.1', 'name' => 'Page View'));
    }

    /**
     * @param \PDO $pdo
     * @param \PDOStatement $pdoStatement
     */
    function it_should_store_escaped_event($pdo, $pdoStatement)
    {
        $hstoreString = '"ip"=>"127.0.0.1","name"=>"Page \"View\""';

        $pdo->prepare('INSERT INTO events (data) VALUES (:data)')->shouldBeCalled()->willReturn($pdoStatement);
        $pdoStatement->bindValue(':data', $hstoreString, \PDO::PARAM_STR)->shouldBeCalled();
        $pdoStatement->execute()->shouldBeCalled();

        $this->save(array('ip' => '127.0.0.1', 'name' => 'Page "View"'));
    }

    /**
     * @param \PDO $pdo
     * @param \PDOStatement $pdoStatement
     */
    function it_should_run_native_query_and_return_empty_array($pdo, $pdoStatement)
    {
        $query = array(
            'query'      => "SELECT data FROM events WHERE (data->'timestamp')::int > :timestamp",
            'parameters' => array(':timestamp' => 12345678)
        );

        $pdo->prepare($query['query'])->shouldBeCalled()->willReturn($pdoStatement);

        $pdoStatement->execute($query['parameters'])->shouldBeCalled();
        $pdoStatement->fetch(\PDO::FETCH_ASSOC)->shouldBeCalled()->willReturn(false);

        $this->runNativeQuery($query, null)->shouldReturn(array());
    }

    function it_should_run_native_query_and_return_result()
    {
//        $resultSet = array(array('id' => 1, 'data' => '"key" => "value \"2\" ", "key2" => "value2"'), false);
//        $a = function() use ($resultSet) {
//            $row = array_shift($resultSet);
//            return null === $row ? false : $row;
//        };
//        $pdoStatement->fetch(\PDO::FETCH_ASSOC)->willReturn($a());

        throw new PendingException('Find a way to iterate db results.');
    }
}
