<?php

namespace spec\Track\Event;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Track\Event\Event;

class EventSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('Page View', array(
            'key' => 'value',
            'timestamp' => '1234567',
            'id' => 'random_string',
        ));
    }

    function it_should_be_event()
    {
        $this->shouldImplement('Track\Event\EventInterface');
        $this->shouldHaveType('Track\Event\Event');
    }

    function its_name_has_initial_value()
    {
        $this->getName()->shouldReturn('Page View');
    }

    function its_name_is_mutable()
    {
        $this->setName('Button Click');
        $this->getName()->shouldReturn('Button Click');
    }

    function its_timestamp_has_initial_value()
    {
        $this->getTimestamp()->shouldReturn('1234567');
        $this->getData()->shouldBeLike(array(
            'key' => 'value',
            'timestamp' => '1234567',
            'id' => 'random_string',
        ));
    }

    function its_timestamp_is_mutable()
    {
        $this->setTimestamp('12345610');

        $this->getTimestamp()->shouldReturn('12345610');
        $this->getData()->shouldBeLike(array(
            'key' => 'value',
            'timestamp' => '12345610',
            'id' => 'random_string',
        ));
    }

    function its_data_has_initial_value()
    {
        $this->getData()->shouldHaveKey('key');
        $this->getData()->shouldContain('value');
        $this->getData()->shouldHaveKey('timestamp');
    }

    function its_data_is_mutable()
    {
        $this->setData(array('key2' => 'value2'));
        $this->getData()->shouldReturn(array('key2' => 'value2'));
    }

    function it_returns_event_array()
    {
        $this->toArray()->shouldHaveKey('key');
        $this->toArray()->shouldContain('value');
        $this->toArray()->shouldHaveKey('name');
        $this->toArray()->shouldContain('Page View');
        $this->toArray()->shouldHaveKey('timestamp');
    }

    function it_should_set_current_timestamp_if_non_given()
    {
        $time = time()-1;
        $this->beConstructedWith('Page View', array('key' => 'value'));

        $this->getTimestamp()->shouldBeBiggerThen($time);
    }

    function it_should_set_random_identifier_if_non_given()
    {
        $this->beConstructedWith('Page View', array('key' => 'value'));

        $this->getId()->shouldBeString();
        $this->getId()->shouldHasLength(Event::DEFAULT_RANDOM_ID_LENGTH);
    }

    public function getMatchers()
    {
        return array(
            'beBiggerThen' => function ($subject, $key) {
                return $subject > $key;
            },
            'hasLength' => function ($subject, $key) {
                return strlen($subject) === $key;
            },
        );
    }
}
