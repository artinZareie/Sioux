<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 11/17/2018
 * Time: 12:44 PM
 */

namespace App\Config;


class Hash
{
    public const HASH_SALT = 'salt!!@#(#)$';
    public const HASH_DEFULT_MAKER = 'bcrypt';
    public const ABLE_TO_BE_STRING_TYPES = ['string', 'integer', 'double', 'float', 'char'];
}