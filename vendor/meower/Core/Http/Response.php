<?php

namespace Meower\Core\Http;


class Response
{
    protected $headers = [];

    protected $status;

    protected $body;

    public function __construct($body = null, $status = 200)
    {
        if (is_string($body)) {
            $this->headers['Content-Length'] = mb_strlen($body);
        }
        $this->body = $body;
        $this->status = $status;
    }

    public function redirect($url)
    {
        $this->status = 302;
        $this->headers['Location'] = $url;

        return $this;
    }

    public function redirectHome()
    {
        $this->status = 302;
        $this->headers['Location'] = Route::$home;

        return $this;
    }

    public function header($headerName, $value)
    {
        $this->headers[$headerName] = $value;
        return $this;
    }

    public function withStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    public function format($format)
    {
        switch ($format) {
            case 'json':
                $this->headers['Content-Type'] = 'application/json';
                $this->body = json_encode($this->body);
                $this->headers['Content-Length'] = mb_strlen($this->body);
        }

        return $this;
    }

    public function getStatusCode()
    {
        return $this->status;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function getHeaderLines()
    {
        return array_map(function ($key, $value) {
            return "$key: $value";
        }, array_keys($this->headers), $this->headers);
    }
}