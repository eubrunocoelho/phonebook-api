<?php

use Middlewares\Cors;

$router->get('/users', 'Controller\AuthController::store')
    ->before(function () {
        Cors::handleCorsHeaders($_SERVER['REQUEST_METHOD']);
        return true;
    });
