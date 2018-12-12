<?php

namespace App\Libraries;


interface Middleware
{
    public function handler(Request $request, ...$values): bool;

    public function fails(Request $request, ...$values);
}