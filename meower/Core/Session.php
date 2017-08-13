<?php

namespace Meower\Core;

class Session
{
    /**
     * @param string $key
     * @return string
     */
    public static function get($key)
    {
        return $_SESSION[$key];
    }

    /**
     * @param string $key
     * @param mixed $value
     */
    public static function put($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    /**
     * @param string $key
     * @return bool
     */
    public static function has($key)
    {
        return isset($_SESSION[$key]) ? true : false;
    }

    /**
     * @param string $key
     */
    public static function delete($key)
    {
        unset($_SESSION[$key]);
    }

    public static function destroy()
    {
        session_destroy();
    }
}
