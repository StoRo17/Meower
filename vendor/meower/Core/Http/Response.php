<?php

namespace Meower\Core\Http;


class Response
{
    /**
     * Associative array of HTTP headers.
     * @var array
     */
    protected $headers = [];

    /**
     * HTTP status of response.
     * @var int
     */
    protected $status = 200;

    /**
     * The body of response.
     * @var mixed
     */
    protected $body;

    /**
     * Redirect to given url.
     * @param string $url
     * @return $this
     */
    public function redirect($url)
    {
        $this->status = 302;
        $this->headers['Location'] = $url;

        return $this;
    }

    /**
     * Redirect to home page.
     * @return $this
     */
    public function redirectHome()
    {
        $this->status = 302;
        $this->headers['Location'] = Route::$home;

        return $this;
    }

    /**
     * Add header to $headers array.
     * @param string $headerName
     * @param string $value
     * @return $this
     */
    public function header($headerName, $value)
    {
        $this->headers[$headerName] = $value;
        return $this;
    }

    /**
     * Set the status.
     * @param int $status
     * @return $this
     */
    public function withStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * Format the body to json etc.
     * @param string $format
     * @return $this
     */
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

    /**
     * Set the body.
     * @param $body
     * @return $this
     */
    public function body($body)
    {
        if (is_string($body)) {
            $this->headers['Content-Length'] = mb_strlen($body);
        }
        $this->body = $body;

        return $this;
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
