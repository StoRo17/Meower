<?php

namespace Meower\Core\Http;


class Response
{
    /**
     * Associative array of HTTP headers.
     *
     * @var array
     */
    protected $headers = [];

    /**
     * HTTP status of response.
     *
     * @var int
     */
    protected $status = 200;

    /**
     * The body of response.
     *
     * @var mixed
     */
    protected $body;

    /**
     * Redirect to given url.
     *
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
     * Add header to $headers array.
     *
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
     * Add headers to $headers array.
     *
     * @param array $headers
     * @return $this
     */
    public function withHeaders($headers)
    {
        foreach ($headers as $header => $value) {
            $this->headers[$header] = $value;
        }
        return $this;
    }

    /**
     * Set the status.
     *
     * @param int $status
     * @return $this
     */
    public function withStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * Format the body to json.
     *
     * @param array $body
     * @return $this
     */
    public function json($body)
    {
        $this->headers['Content-Type'] = 'application/json';
        $this->body = json_encode($body);
        $this->headers['Content-Length'] = mb_strlen($this->body);

        return $this;
    }

    /**
     * Set the body.
     *
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

    /**
     * Set the cookie.
     *
     * @param string $name
     * @param string $value
     * @param int    $minutes
     * @param string $path
     * @param string $domain
     * @param bool   $secure
     * @param bool   $httpOnly
     * @return $this
     */
    public function cookie($name, $value, $minutes, $path = "", $domain = "", $secure = false, $httpOnly = true)
    {
        setcookie($name, $value, time()+60*$minutes, $path, $domain, $secure, $httpOnly);

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

    public function getHeaders()
    {
        return $this->headers;
    }

    public function getHeaderLines()
    {
        return array_map(function ($key, $value) {
            return "$key: $value";
        }, array_keys($this->headers), $this->headers);
    }
}
