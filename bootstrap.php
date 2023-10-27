<?php

require_once __DIR__ . '/functions/functions.php';
require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/autoload.php';

use lib\Dispatcher;
use lib\Router;

$method = $_SERVER['REQUEST_METHOD'];
$path = $_SERVER['PATH_INFO'] ?? '/';

$router = new Router($method, $path);

require_once __DIR__ . '/routes/api.php';

$dispatcher = new Dispatcher($router);
$dispatcher->dispatch();