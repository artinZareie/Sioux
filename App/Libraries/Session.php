<?php

namespace App\Libraries;


class Session
{
    public function __get($name)
    {
        return $_SESSION[$name];
    }

    public static function all()
    {
        return $_SESSION;
    }

    public static function except(string $name)
    {
        $outer = $_SESSION;
        if (isset($outer[$name]))
            unset($outer[$name]);
        return $outer;
    }

    public static function destroy()
    {
        session_unset();
        session_destroy();
    }

    public static function destroy_one($name)
    {
        unset($_SESSION[$name]);
    }

    public static function set(string $name, $value)
    {
        $_SESSION[$name] = $value;
    }

    public static function get(string $name)
    {
        return $_SESSION[$name];
    }

    public static function set_flash(string $name, $value, int $time)
    {
        if (!isset($_SESSION['FLASH_TIMES'])) {
            $_SESSION['FLASH_TIMES'] = [];
        }
        $_SESSION['FLASH_TIMES'][$name] = [
            'value' => $value,
            'start_time' => time(),
            'long' => $time
        ];
    }

    public static function get_flash(string $name)
    {
        if (isset($_SESSION['FLASH_TIMES'][$name]) && $_SESSION['FLASH_TIMES'][$name]['start_time'] + $_SESSION['FLASH_TIMES'][$name]['long'] - time() > 0)
            return $_SESSION['FLASH_TIMES'][$name]['value'];
        else {
            self::destroy_one_flash($name);
            return null;
        }
    }

    public static function destroy_one_flash(string $name)
    {
        unset($_SESSION['FLASH_TIMES'][$name]);
    }
}