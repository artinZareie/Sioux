<?php

namespace App\Libraries;


use App\Config\HTTP;

class Cookie
{
    private static $hashing = HTTP::HASH_COOKIES;

    public function __get($name)
    {
        return $_COOKIE[$name];
    }

    public static function hashing_off()
    {
        self::$hashing = false;
    }

    public static function hashing_on()
    {
        self::$hashing = true;
    }

    public static function hashing_status()
    {
        return self::$hashing;
    }

    public static function set(string $name, string $value, int $expires = null, $path = '/', string $domin = null, $secured = false, $httponly = false)
    {
        setcookie($name, (self::$hashing ? base64_encode($value) : $value), $expires, $path, $domin, $secured, $httponly);
    }

    public static function destroy()
    {
        foreach ($_COOKIE as $cookie => $_) {
            self::destroy_one($cookie);
        }
    }

    public static function get(string $name)
    {
        if (isset($_COOKIE[$name])) {
            return (self::$hashing && Hash::is_base64($_COOKIE[$name]) ? base64_decode($_COOKIE[$name]) : $_COOKIE[$name]);
        }
        return true;
    }

    public static function except(string $name)
    {
        $outer = $_COOKIE;
        if (isset($outer[$name]))
            unset($outer[$name]);
        foreach ($outer as $key => $val) {
            if (self::$hashing && Hash::is_base64($val))
                $outer[$key] = base64_decode($val);
        }
        return $outer;
    }

    public static function excepts(array $names)
    {
        $outer = $_COOKIE;
        foreach ($names as $name) {
            if (isset($outer[$name]))
                unset($outer[$name]);
        }
        foreach ($outer as $key => $val) {
            if (self::$hashing && Hash::is_base64($val))
                $outer[$key] = base64_decode($val);
        }
        return $outer;
    }

    public static function all()
    {
        $outer = $_COOKIE;
        foreach ($outer as $key => $val) {
            if (self::$hashing && Hash::is_base64($val))
                $outer[$key] = base64_decode($val);
        }
        return $outer;
    }

    public static function destroy_one($name)
    {
        unset($_COOKIE[$name]);
        self::set($name, '', -1, '/');
    }
}