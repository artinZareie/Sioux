<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 11/12/2018
 * Time: 11:30 AM
 */

namespace App\Libraries;


interface Middleware
{
    public function handler(Request $request, ...$values): bool;

    public function fails(Request $request, ...$values);
}