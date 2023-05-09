<?php

namespace App\Core;

class Session
{
    public static function init()
    {
        session_start();
    }

    public static function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public static function get($key, $secondKey = false)
    {
        if ($secondKey == true) {
            if (isset($_SESSION[$key][$secondKey])) {
                return $_SESSION[$key][$secondKey];
            }
        } else {
            if (isset($_SESSION[$key])) {
                return $_SESSION[$key];
            }
        }
        return false;
    }

    public static function destroy()
    {
        session_destroy();
    }

    public static function unset($key)
    {
        unset($_SESSION[$key]);
    }
}