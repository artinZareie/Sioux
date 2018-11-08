<?php


namespace App\HTTP\Controllers;
use App\Libraries\Hash;

class Main
{
    public function index()
    {
        echo Hash::make("1222333e22");
    }
}