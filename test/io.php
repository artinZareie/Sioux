<?php

require_once '../App/Libraries/Boof.php';

$boof = new \App\Libraries\Boof(__DIR__.'/../App/HTTP/Views');
var_dump($boof->view('view'));