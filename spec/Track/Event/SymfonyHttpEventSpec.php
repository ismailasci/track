<?php

namespace spec\Track\Event;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class SymfonyHttpEventSpec extends ObjectBehavior
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\HttpFoundation\ServerBag $server
     */
    function let($request, $server)
    {
        $request->getClientIp()->shouldBeCalled()->willReturn('127.0.0.1');
        $request->getUri()->shouldBeCalled()->willReturn('http://www.example.com');
        $request->server = $server;

        $server->get('HTTP_REFERER')->shouldBeCalled()->willReturn(null);

        $this->beConstructedWith($request, 'Page View', array('timestamp' => '1234567', 'id' => 'random'));
    }

    function it_should_be_event()
    {
        $this->shouldImplement('Track\Event\EventInterface');
        $this->shouldHaveType('Track\Event\HttpEvent');
    }

    function it_should_return_event_data()
    {
        $this->toArray()->shouldBeLike(array(
            'name' => 'Page View',
            'ip' => '127.0.0.1',
            'url' => 'http://www.example.com',
            'referer' => null,
            'timestamp' => '1234567',
            'id' => 'random'
        ));
    }
}
