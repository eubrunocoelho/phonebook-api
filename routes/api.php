<?php

use Middlewares\Cors;

$router->post('/users', 'Controller\AuthController::store')
    ->before(function () {
        Cors::handleCorsHeaders($_SERVER['REQUEST_METHOD']);
        return true;
    });

$router->get('/users', 'Controller\AuthController::index');