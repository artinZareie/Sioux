<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 11/12/2018
 * Time: 11:30 AM
 */

namespace App\Libraries;


interface Middleware
{
    public function up();
    public function down();
}