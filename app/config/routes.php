<?php

use App\Libraries\Router;
use App\Libraries\Request;
use App\Libraries\Response;
use App\Libraries\Crash;


Router::get('/', function (Request $request) {
    $tmp_eng = new Crash('home');
    $tmp_eng->run();
});

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
        var_dump($request);
        make_error($id);
    });
});
