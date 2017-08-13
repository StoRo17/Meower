<?php

namespace Meower\Core;

class Session
{
    /**
     * Session constructor.
     */
    public function __construct()
    {
        session_start();
    }

    /**
     * @param string $key
     * @return string
     */
    public function get($key)
    {
        return $_SESSION[$key];
    }

    /**
     * @param string $key
     * @param mixed $value
     */
    public function put($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    /**
     * @param string $key
     * @return bool
     */
    public function has($key)
    {
        return isset($_SESSION[$key]) ? true : false;
    }

    /**
     * @param string $key
     */
    public function delete($key)
    {
        unset($_SESSION[$key]);
    }

    public function destroy()
    {
        session_destroy();
    }
}
