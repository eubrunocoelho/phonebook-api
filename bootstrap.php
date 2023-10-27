<?php

require_once __DIR__ . '/functions/functions.php';
require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/src/lib/autoload.php';

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
} elseif (is_string($data['action'])) {
    $data['action'] = explode('::', $data['action']);
    $controller = new $data['action'][0];
    $action = $data['action'][1];

    echo $controller->$action($router->getParams());
}

foreach ($data['after'] as $after) {
    if (!$after($router->getParams())) {
        die();
    }
}
