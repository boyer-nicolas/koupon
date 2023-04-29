<?php

namespace Koupon\Model;

require_once 'vendor/autoload.php';

use Koupon\Model\Auth;

$auth = new Auth();

$jwt = $auth->generateJWT();
echo $jwt;
