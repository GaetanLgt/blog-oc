<?php

namespace App\Core;

class Session
{
    public static function init(): void
    {
        session_start();
    }

    public static function set($key, $value): void
    {
        $_SESSION[$key] = $value;
    }

    public static function get($key, $secondKey = false): bool|string
    {
        if ($secondKey === true) {
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

    public static function destroy(): void
    {
        unset($_SESSION);
        unset($_COOKIE);
        unset($_POST);
        session_destroy();
    }

    public static function unset($key): void
    {
        unset($_SESSION[$key]);
    }
}