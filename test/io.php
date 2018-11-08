<?php


include_once "../App/Libraries/Hash.php";

use App\Libraries\Hash;

echo Hash::crypt("dcadc",Hash::bcrypt("..."));