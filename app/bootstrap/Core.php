<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 11/8/2018
 * Time: 4:05 AM
 */

namespace App\Bootstrap;
use App\Libraries\Router;


class Core
{
    public function __construct()
    {
        var_dump(Router::$routes);
    }
}