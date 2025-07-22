<?php

// Configuration production
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

require_once "../vendor/autoload.php";
require_once "../app/config/bootstrap.php";
require_once '../route/route.web.php';

use App\Core\Router;

Router::resolve($routes);
