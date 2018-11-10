<?php

require_once APP_DIR . "Libraries/Router.php";

use App\Libraries\Router;

Router::get('/' , function () {
    echo "Say Hello !!!";
});

Router::all('/world/(\d+)', 'MainController@index');

Router::all('/hello/(\d+)', function ($id) {
    echo "Hello id " . $id;
});

Router::all('/[a-zA-Z]+/[0-9]+/(\d+)', function ($id) {
    echo "Hello id guy " . $id;
});