<?php

namespace App\Config;


use App\Libraries\Packages\AngularStrong;
use App\Libraries\Packages\CLI;

class Package
{
    const autoload_package = [
        CLI::class,
        AngularStrong::class
    ];
}