<?php

namespace Meower\Core\Http;


class Request
{
    /**
     * Wrapper over $_GET.
     * @var array
     */
    public $query = [];

    /**
     * Wrapper over $_POST.
     * @var array
     */
    public $post = [];

    /**
     * Wrapper over $_REQUEST.
     * @var array
     */
    public $request = [];

    /**
     * Wrapper over $_COOKIE.
     * @var array
     */
    public $cookie = [];

    /**
     * Wrapper over $_FILES.
     * @var array
     */
    public $files = [];

    /**
     * Wrapper over $_SERVER.
     * @var array
     */
    public $server = [];

    /**
     * Request constructor.
     */
    public function __construct()
    {
        $this->query = $_GET;
        $this->post = $_POST;
        $this->request = $_REQUEST;
        $this->cookie = $_COOKIE;
        $this->files = $_FILES;
        $this->server = $_SERVER;
        unset($_GET, $_POST, $_REQUEST, $_COOKIE, $_FILES, $_SERVER);
    }

    public function url()
    {
        return $this->server['REQUEST_URI'];
    }

    public function fullUrl()
    {
        return $this->server['HTTP_HOST'] . $this->url();
    }

    public function method()
    {
        if ($this->server['REQUEST_METHOD'] == 'POST' && array_key_exists('_method', $this->post)) {
            return strtoupper($this->post['_method']);
        } else {
            return $this->server['REQUEST_METHOD'];
        }
    }

    public function isMethod($method)
    {
        return $this->method() == strtoupper($method) ? true : false;
    }
}