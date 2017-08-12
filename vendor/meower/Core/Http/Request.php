<?php

namespace Meower\Core\Http;

class Request
{
    /**
     * Wrapper over $_GET.
     *
     * @var array
     */
    public $query = [];

    /**
     * Wrapper over $_POST.
     *
     * @var array
     */
    public $post = [];

    /**
     * Wrapper over $_REQUEST.
     *
     * @var array
     */
    public $request = [];

    /**
     * Wrapper over $_COOKIE.
     *
     * @var array
     */
    public $cookie = [];

    /**
     * Wrapper over $_FILES.
     *
     * @var array
     */
    public $files = [];

    /**
     * Wrapper over $_SERVER.
     *
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
        $this->files = $this->normalizeFilesArray($_FILES);
        $this->server = $_SERVER;
        unset($_GET, $_POST, $_REQUEST, $_COOKIE, $_FILES, $_SERVER);
    }

    /**
     * Return path of requested url.
     *
     * @return string
     */
    public function url()
    {
        return $this->server['REQUEST_URI'];
    }

    /**
     * Return all path of requested url.
     *
     * @return string
     */
    public function fullUrl()
    {
        return $this->server['HTTP_HOST'] . $this->url();
    }

    /**
     * Return requested method.
     *
     * @return string
     */
    public function method()
    {
        if ($this->server['REQUEST_METHOD'] == 'POST' && array_key_exists('_method', $this->post)) {
            return strtoupper($this->post['_method']);
        } else {
            return $this->server['REQUEST_METHOD'];
        }
    }

    /**
     * @param string $method
     * @return bool
     */
    public function isMethod($method)
    {
        return $this->method() == strtoupper($method) ? true : false;
    }

    public function all()
    {
        return $this->post;
    }

    /**
     * Return an input value that is located in the POST array
     * as a string or array.
     *
     * @param string $key
     * @param string $default
     * @return string|array
     */
    public function input($key, $default = "")
    {
        if ($this->PostArrayHas($key)) {
            $input = explode('.', $key);
            $value = $this->post[$input[0]];

            if (count($input) > 1) {
                foreach (array_slice($input, 1) as $inputKey) {
                    if (is_numeric($inputKey)) {
                        $inputKey = (int)$inputKey;
                    }
                    $value = $value[$inputKey];
                }
            }
            return $value;
        } else {
            return $default;
        }
    }

    /**
     * @param string $key
     * @return string|array|null
     */
    public function __get($key)
    {
        return $this->PostArrayHas($key) ? $this->post[$key] : null;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function PostArrayHas($key)
    {
        return isset($this->post[$key]) ? true : false;
    }

    /**
     * @param array $files
     * @return array
     */
    private function normalizeFilesArray($files)
    {
        $normalizedArray = [];
        foreach ($files as $key => $file) {
            if (!is_array($file['name'])) {
                $normalizedArray[$key] = $file;
                continue;
            }

            foreach ($file['name'] as $index => $name) {
                $normalizedArray[$key][$index] = [
                    'name' => $name,
                    'type' => $file['type'][$index],
                    'tmp_name' => $file['tmp_name'][$index],
                    'error' => $file['error'][$index],
                    'size' => $file['size'][$index]
                ];
            }
        }

        return $normalizedArray;
    }

    /**
     * Return file(s) information.
     *
     * @param string $filename
     * @return array
     */
    public function file($filename)
    {
        return $this->files[$filename];
    }

    /**
     * @param string $filename
     * @return bool
     */
    public function hasFile($filename)
    {
        return isset($this->files[$filename]) ? true : false;
    }
}