<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 11/21/2018
 * Time: 8:18 AM
 */

namespace App\HTTP\Middleware;


use App\Libraries\Middleware;
use App\Libraries\Request;
use App\Libraries\Response;

class CORS implements Middleware
{
    public function handler(Request $request, ...$params): bool
    {
        Response::set_header('Access-Control-Allow-Origin:*');
        Response::set_header('Access-Control-Allow-Headers:X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding');
        return true;
    }

    public function fails(Request $request, ...$params)
    {
        return null;
    }
}