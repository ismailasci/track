<?php

namespace Track\Event;

class HttpEvent extends Event implements EventInterface
{
    protected $ip;

    protected $url;

    protected $referer;

    public function __construct($name, array $data = null)
    {
        parent::__construct($name, $data);

        $this->setIp(isset($data['ip']) ? $data['ip'] : $this->determineIp());
        $this->setUrl(isset($data['url']) ? $data['url'] : $this->determineUrl());
        $this->setReferer(isset($data['referer']) ? $data['referer'] : $this->determineReferer());
    }

    /**
     * @param mixed $ip
     */
    public function setIp($ip)
    {
        $this->ip = $ip;
        $this->data['ip'] = $ip;
    }

    /**
     * @return mixed
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * @param mixed $referer
     */
    public function setReferer($referer)
    {
        $this->referer = $referer;
        $this->data['referer'] = $referer;
    }

    /**
     * @return mixed
     */
    public function getReferer()
    {
        return $this->referer;
    }

    /**
     * @param mixed $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
        $this->data['url'] = $url;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    protected function determineIp()
    {
        $ip = null;

        if (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        if (null !== $ip) {
            $ip = filter_var($ip, FILTER_VALIDATE_IP);
        }

        return (false === $ip || null === $ip) ? null : $ip;
    }

    protected function determineUrl()
    {
        $url = '';

        if (isset($_SERVER['HTTPS']) && (strtolower($_SERVER['HTTPS']) === 'on' || $_SERVER['HTTPS'] === '1')) {
            $url .= 'https://';
        } else {
            $url .= 'http://';
        }

        if (isset($_SERVER['SERVER_NAME'])) {
            $url .=  $_SERVER['SERVER_NAME'];
        }

        if (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] != 80 && $_SERVER['SERVER_PORT'] != 443) {
            $url .= ":{$_SERVER['SERVER_PORT']}";
        }

        if (isset($_SERVER['REQUEST_URI'])) {
            $url .= $_SERVER['REQUEST_URI'];
        }

        return empty($url) ? null : $url;
    }

    protected function determineReferer()
    {
        return isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null;
    }
}
