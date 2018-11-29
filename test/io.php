<?php

$input = "hello world !! 11 12 13";
preg_replace_callback('/\d+/',function ($match) {
    var_dump($match);
}, $input);