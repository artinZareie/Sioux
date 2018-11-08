<?php

namespace Bootstrap;

require_once(__DIR__ . "\\..\\config\\App.php");
require_once(__DIR__ . "\\..\\libraries\\system.php");

use Config\App;

class Boot
{
    public function __construct()
    {
        App::make_asset("index.css", "css");
    }
}