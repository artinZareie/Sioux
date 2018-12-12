<?php

namespace App\Config;


class Database
{
    public const CONNECTION_HOST = 'localhost';
    public const CONNECTION_USERNAME = 'root';
    public const CONNECTION_PASSWORD = '';
    public const PDO_DRIVER = 'mysql';
    public const PDO_CONNECTION_SET_COLLECTION = 'utf8';
    public const PDO_CONNECTION_DATABASE = 'sioux';
    public const PDO_OPTIONS = [
        \PDO::ATTR_ERRMODE => \PDO::ERRMODE_WARNING,
        \PDO::ATTR_PERSISTENT => true
    ];
}