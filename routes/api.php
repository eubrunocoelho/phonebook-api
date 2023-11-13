<?php

use Middlewares\Authentication;
use Middlewares\Cors;

$router->post('/register', 'Controllers\AuthController::register')
    ->before(function () {
        Cors::handleCorsHeaders($_SERVER['REQUEST_METHOD']);
        return true;
    });

$router->post('/login', 'Controllers\AuthController::login')
    ->before(function () {
        Cors::handleCorsHeaders($_SERVER['REQUEST_METHOD']);
        return true;
    });

$router->get('/contacts', 'Controllers\ContactController::index')
    ->before(function () {
        Authentication::authorization();

        return false;
    });
