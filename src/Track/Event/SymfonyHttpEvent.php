<?php

namespace Track\Event;

use Symfony\Component\HttpFoundation\Request;

class SymfonyHttpEvent extends HttpEvent
{
    private $request;

    public function __construct(Request $request, $name, array $data = null)
    {
        $this->request = $request;

        if (null === $data) {
            $data = array();
        }

        $data['ip']      = $request->getClientIp();
        $data['url']     = $request->getUri();
        $data['referer'] = $request->server->get('HTTP_REFERER');

        parent::__construct($name, $data);
    }

    public function getRequest()
    {
        return $this->request;
    }
}
