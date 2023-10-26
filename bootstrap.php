<?php

require_once __DIR__ . '/helpers/functions.php';
require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/src/lib/autoload.php';

use lib\Router;

$method = $_SERVER['REQUEST_METHOD'];
$path = $_SERVER['PATH_INFO'] ?? '/';

$router = new Router($method, $path);

$router->get('/', function () {
    return 'Olá, mundo!';
});

$result = $router->handler();

if (!$result) {
    http_response_code(404);
    echo 'Página não encontrada!';
    die();
}

$data = $result->getData();

foreach ($data['before'] as $before) {
    if (!$before($router->getParams())) {
        die();
    }
}

if ($data['action'] instanceof Closure) {
    echo $data['action']($router->getParams());
}

// ...

foreach ($data['after'] as $after) {
    if (!$after($router->getParams())) {
        die();
    }
}
