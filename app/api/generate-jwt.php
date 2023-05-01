<?php

namespace Koupon\Model;

require_once 'vendor/autoload.php';

use Koupon\Model\Auth;

$auth = new Auth();

$jwt = $auth->generateJWT();

echo "Here is your JWT: \n";
echo $jwt;
echo "\n\n";
