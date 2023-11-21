<?php

session_start();

require_once __DIR__ . '/app/settings.php';

require_once DIR_APP . '/functions/functions.php';
require_once DIR_APP . '/autoload.php';

use lib\Dispatcher;
use lib\Router;

$method = $_SERVER['REQUEST_METHOD'];
$path = $_SERVER['PATH_INFO'] ?? '/';

$router = new Router($method, $path);
$dependencies = require_once DIR_APP . '/app/dependencies.php';

require_once DIR_APP . '/routes/api.php';

$dispatcher = new Dispatcher($router, $dependencies);
$dispatcher->dispatch();
