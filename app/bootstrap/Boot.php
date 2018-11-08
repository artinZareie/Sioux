<?php

namespace Bootstrap;

require_once(__DIR__ . '\\..\\config\\App.php');
require_once(APP_DIR . 'libraries' . SLASH . "system.php");

use Config\App;

class Boot
{
    public function __construct()
    {
        echo file_get_contents(App::make_asset("index.css", "css"));
    }
}