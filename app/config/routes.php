<?php

use App\Libraries\Router;

Router::get('/' , function () {
    return \App\Libraries\Response::json(['artin'=>'baba']);
});

Router::all('/world/(\d+)', 'MainController@index');

Router::all('/hello/(\d+)', function ($id) {
    return "Hello id " . $id;
});

Router::all('/[a-zA-Z]+/[0-9]+/(\d+)', function ($id) {
    return "Hello id guy " . $id;
});