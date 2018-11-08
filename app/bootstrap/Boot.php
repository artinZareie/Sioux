<?php

namespace App\Bootstrap;

require_once(__DIR__ . '\\..\\config\\App.php');
require_once(APP_DIR . 'libraries' . SLASH . "system.php");
require_once(APP_DIR . "Bootstrap" . SLASH . "AutoLoader.php");

use App\Config\App;
use App\Bootstrap\AutoLoader;
use App\HTTP\Controllers\Main;

class Boot
{
    public function __construct()
    {
        $this->autoload();
        call_user_func_array([new Main(), "index"], []);
    }

    public function autoload()
    {
        AutoLoader::make();
    }
}