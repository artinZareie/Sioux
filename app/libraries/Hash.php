<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 11/8/2018
 * Time: 2:27 AM
 */

namespace App\Libraries;

use App\Config\Hash as Conf;


class Hash
{
    public static function make($str, $salt = Conf::HASH_SALT): string
    {
        if (method_exists(self::class, Conf::HASH_DEFULT_MAKER)) {
            switch (Conf::HASH_DEFULT_MAKER) {
                case "crypt":
                    return self::crypt($str, $salt);
                    break;
                case "sha1":
                    return self::sha1($str);
                    break;
                case "bcrypt":
                    return self::bcrypt($str);
                    break;
                case "md5":
                    return self::md5($str);
                    break;
                default:
                    make_error("No one of hashes matched !!!", "In the App config you givenn " . Conf::HASH_DEFULT_MAKER . " but this function in Hash class does not exists");
                    break;
            }
        } else
            make_error("No one of hashes matched !!!", "In the App config you givenn " . Conf::HASH_DEFULT_MAKER . " but this function in Hash class does not exists");
        return "";
    }

    public static function bcrypt(string $string, int $type = PASSWORD_BCRYPT, array $options = []): string
    {
        return password_hash($string, $type, $options);
    }

    public static function bcrypt_equals(string $hashed, string $text): bool
    {
        return password_verify($hashed, $text);
    }

    public static function crypt(string $string, string $salt = Conf::HASH_SALT): string
    {
        return crypt($string, $salt);
    }

    public static function md5(string $string): string
    {
        return md5($string);
    }

    public static function sha1(string $string): string
    {
        return sha1($string);
    }

    public static function is_base64(string $hashed_code): bool
    {
        if (base64_encode(base64_decode($hashed_code)) === $hashed_code) {
            return true;
        } else {
            return false;
        }
    }
}