<?php

namespace App\HTTP\Middleware;


use App\Libraries\Middleware;
use App\Libraries\Request;
use App\Libraries\Response;

class AgeMiddleware implements Middleware
{

    public function handler(Request $request, ...$first): bool
    {
        if (isset($request->age))
            return false;
        elseif (!$request->age <= 10) {
            return true;
        }
    }

    public function fails(Request $request, ...$first)
    {
        return json_encode([
            'error' => 'errorname'
        ]);
    }
}