<?php

use App\Libraries\Router;
use App\Libraries\Request;


Router::get('/', 'MainController@index');

Router::redirect('/re', '/12/11');

Router::get('/(\d+)/(\d+)', function () {
    return "
    <html>
        <body>
        <p>hello world !!!</p>
</body>
    </html>
    ";
});

Router::group(['prefix' => 'hello'], function ($routes) {
    $routes->get('/world/(\d+)', function (Request $request, int $id) {
        make_error($id);
    });
});
