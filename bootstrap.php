<?php

require_once __DIR__ . '/functions/functions.php';
require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/src/lib/autoload.php';

use lib\Dispatcher;
use lib\Router;

$method = $_SERVER['REQUEST_METHOD'];
$path = $_SERVER['PATH_INFO'] ?? '/';

$router = new Router($method, $path);

$router->get('/', function () {
    return 'Olá, mundo!';
});

$router->get('/testing/{ID}', 'Controller\IndexController::index')
    ->before(function () {
        $checkUserIsAuth = true;

        if (!$checkUserIsAuth) {
            http_response_code(401);

            echo 'Você não está autenticado';
        }

        return $checkUserIsAuth;
    })
    ->before(function () {
        echo 'Segundo middleware';

        return true;
    })
    ->after(function () {
        echo 'Finalização';

        return true;
    });

//
$dispatcher = new Dispatcher($router);
$dispatcher->dispatch();