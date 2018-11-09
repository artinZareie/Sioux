<?php

require_once APP_DIR."Libraries/Router.php";
use App\Libraries\Router;

Router::get('/hello',function () {
    echo "s";
});

Router::all('/hello',function () {
    echo "s";
});

Router::delete('/del',function () {
    echo "s";
});