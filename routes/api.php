<?php

use Middlewares\Cors;

$router->post('/users', 'Controllers\AuthController::register')
    ->before(function () {
        Cors::handleCorsHeaders($_SERVER['REQUEST_METHOD']);
        return true;
    });

$router->post('/login', 'Controllers\AuthController::login')
    ->before(function () {
        Cors::handleCorsHeaders($_SERVER['REQUEST_METHOD']);
        return true;
    });
