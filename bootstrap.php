<?php

require_once __DIR__ . '/functions/functions.php';
require_once __DIR__ . '/config/settings.php';
require_once __DIR__ . '/autoload.php';

use lib\ConnectionFactory;
use lib\Dispatcher;
use lib\Router;

$method = $_SERVER['REQUEST_METHOD'];
$path = $_SERVER['PATH_INFO'] ?? '/';

$router = new Router($method, $path);
$dependencies = require_once __DIR__ . '/config/dependencies.php';

require_once __DIR__ . '/routes/api.php';

$dispatcher = new Dispatcher($router, $dependencies);
$dispatcher->dispatch();