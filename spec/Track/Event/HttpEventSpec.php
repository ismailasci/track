<?php

namespace spec\Track\Event;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class HttpEventSpec extends ObjectBehavior
{
    private $initialData = array(
        'key' => 'value',
        'ip' => '127.0.0.1',
        'url' => 'http://www.example.com/',
        'referer' => 'http://www.referer.com/',
        'timestamp' => '1234567',
    );

    function let()
    {
        $this->beConstructedWith('Page View', $this->initialData);
    }

    function it_should_be_event()
    {
        $this->shouldImplement('Track\Event\EventInterface');
        $this->shouldHaveType('Track\Event\Event');
    }

    function its_ip_has_initial_value()
    {
        $this->getIp()->shouldReturn('127.0.0.1');
        $this->getData()->shouldBeLike($this->initialData);
    }

    function its_ip_is_mutable()
    {
        $this->setIp('192.168.0.1');
        $this->initialData['ip'] = '192.168.0.1';

        $this->getIp()->shouldReturn('192.168.0.1');
        $this->getData()->shouldBeLike($this->initialData);
    }

    function its_url_has_initial_value()
    {
        $this->getUrl()->shouldReturn('http://www.example.com/');
        $this->getData()->shouldBeLike($this->initialData);
    }

    function its_url_is_mutable()
    {
        $this->setUrl('http://www.domain.com/');
        $this->initialData['url'] = 'http://www.domain.com/';

        $this->getUrl()->shouldReturn('http://www.domain.com/');
        $this->getData()->shouldBeLike($this->initialData);
    }

    function its_referer_has_initial_value()
    {
        $this->getReferer()->shouldReturn('http://www.referer.com/');
        $this->getData()->shouldBeLike($this->initialData);
    }

    function its_referer_is_mutable()
    {
        $this->setReferer('http://www.domain.com/');
        $this->initialData['referer'] = 'http://www.domain.com/';

        $this->getReferer()->shouldReturn('http://www.domain.com/');
        $this->getData()->shouldBeLike($this->initialData);
    }

    function it_should_determine_ip()
    {
        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
        unset($this->initialData['ip']);

        $this->beConstructedWith('Page View', $this->initialData);

        $this->getIp()->shouldReturn('127.0.0.1');
    }

    function it_should_determine_url()
    {
        $_SERVER['HTTPS'] = 'on';
        $_SERVER['SERVER_NAME'] = 'example.com';
        $_SERVER['SERVER_PORT'] = 443;
        $_SERVER['REQUEST_URI'] = '/index.html';

        unset($this->initialData['url']);

        $this->beConstructedWith('Page View', $this->initialData);

        $this->getUrl()->shouldReturn('https://example.com/index.html');
    }

    function it_should_determine_referer()
    {
        $_SERVER['HTTP_REFERER'] = 'http://referer.com';
        unset($this->initialData['referer']);

        $this->beConstructedWith('Page View', $this->initialData);

        $this->getReferer()->shouldReturn('http://referer.com');
    }

    function letGo()
    {
        unset($_SERVER['REMOTE_ADDR']);
        unset($_SERVER['HTTPS']);
        unset($_SERVER['SERVER_NAME']);
        unset($_SERVER['SERVER_PORT']);
        unset($_SERVER['REQUEST_URI']);
        unset($_SERVER['HTTP_REFERER']);
    }
}
